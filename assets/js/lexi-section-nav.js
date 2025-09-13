/**
 * Lexi Section Navigator JavaScript
 *
 * @package LetLexi\Toolkit
 * @version 1.0.0
 */

(function() {
    'use strict';
    
    // Configuration from localized data
    var config = window.letlexiSectionNav || {};
    var restUrl = config.restUrl;
    var postId = config.postId;
    var totalSections = config.totalSections || 0;
    var i18n = config.i18n || {};
    
    // Fallback i18n strings for Elementor editor context
    if (!i18n.showCommentary) {
        i18n.showCommentary = 'Show Commentary';
        i18n.hideCommentary = 'Hide Commentary';
    }
    
    // State management
    var currentIndex = 0;
    var cache = {};
    var isNavigating = false;
    
    // DOM elements
    var docWrapper = document.querySelector('.lexi-doc');
    var contentBody = document.querySelector('.lexi-doc__body');
    var tocLinks = document.querySelectorAll('.lexi-toc__link');
    var prevBtn = document.querySelector('.lexi-nav__prev');
    var nextBtn = document.querySelector('.lexi-nav__next');
    var jumpSelect = document.querySelector('.lexi-jump__select');
    var tocToggle = document.querySelector('.lexi-toc__toggle');
    var tocList = document.querySelector('.lexi-toc__list');
    var toc = document.querySelector('.lexi-toc');
    var printBtn = document.querySelector('.lexi-print-btn');
    var copyCitationBtn = document.querySelector('.lexi-copy-citation-btn');
    
    // Initialize on DOM ready
    function init() {
        // In Elementor editor, we might not have the main wrapper elements
        // but we should still initialize commentary toggles if they exist
        if (!docWrapper && !contentBody) {
            // Try to find elements in the current context
            docWrapper = document.querySelector('.lexi-doc');
            contentBody = document.querySelector('.lexi-doc__body');
        }
        
        // If we still don't have the main elements, just initialize commentary toggles
        if (!docWrapper || !contentBody) {
            bindCommentaryToggles();
            return;
        }
        
        // Read initial section from URL
        var initialIndex = getInitialSectionIndex();
        
        // Set up event listeners
        bindEvents();
        
        // Derive totalSections from DOM when not provided (manual/pre-rendered mode)
        if (!totalSections) {
            var tocItems = document.querySelectorAll('.lexi-toc__link');
            totalSections = tocItems ? tocItems.length : 0;
        }

        // Load initial section if not already rendered and API is available
        if (initialIndex >= 0 && !hasContent() && restUrl && postId && totalSections > 0) {
            navigateTo(initialIndex, false);
        } else {
            updateNavigation();
        }
        
        // Prefetch next section after idle
        if ('requestIdleCallback' in window) {
            requestIdleCallback(prefetchNext);
        }
        
    }
    
    // Get initial section index from URL
    function getInitialSectionIndex() {
        var urlParams = new URLSearchParams(window.location.search);
        var secParam = urlParams.get('sec');
        
        if (secParam !== null) {
            return Math.max(0, Math.min(parseInt(secParam, 10), totalSections - 1));
        }
        
        // Check for hash like #sec-5
        var hash = window.location.hash;
        // Support both #sec-5 and #sec-123-5 by capturing the last number
        var hashMatch = hash.match(/#sec-(?:\d+-)?(\d+)/);
        if (hashMatch) {
            return Math.max(0, Math.min(parseInt(hashMatch[1], 10), totalSections - 1));
        }
        
        return 0;
    }
    
    // Check if content is already rendered (SSR)
    function hasContent() {
        return contentBody && contentBody.innerHTML.trim().length > 0 && 
               !contentBody.querySelector('.lexi-loading');
    }
    
    // Bind event listeners
    function bindEvents() {
        // TOC navigation
        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var index = parseInt(this.dataset.index, 10);
                if (!isNaN(index)) {
                    navigateTo(index);
                }
            });
        });
        
        // Previous/Next buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentIndex > 0) {
                    navigateTo(currentIndex - 1);
                }
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentIndex < totalSections - 1) {
                    navigateTo(currentIndex + 1);
                }
            });
        }
        
        // Jump select
        if (jumpSelect) {
            jumpSelect.addEventListener('change', function() {
                var index = parseInt(this.value, 10);
                if (!isNaN(index)) {
                    navigateTo(index);
                }
            });
        }
        
        
        // TOC toggle for mobile
        if (tocToggle && tocList) {
            tocToggle.addEventListener('click', function() {
                var isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                // Toggle both patterns to support different CSS expectations
                docWrapper.classList.toggle('toc-expanded', !isExpanded);
                if (toc) {
                    toc.classList.toggle('expanded', !isExpanded);
                }
            });
        }

        // Print button functionality
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                printCurrentSection();
            });
        }

        // Copy citation button functionality
        if (copyCitationBtn) {
            copyCitationBtn.addEventListener('click', function() {
                copyCitationToClipboard();
            });
        }
        
        // Commentary toggle functionality
        bindCommentaryToggles();
        
        // Browser back/forward
        window.addEventListener('popstate', function(e) {
            if (e.state && typeof e.state.sectionIndex === 'number') {
                currentIndex = e.state.sectionIndex;
                loadSection(currentIndex, false);
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!docWrapper.contains(document.activeElement)) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    if (currentIndex > 0) {
                        e.preventDefault();
                        navigateTo(currentIndex - 1);
                    }
                    break;
                case 'ArrowRight':
                    if (currentIndex < totalSections - 1) {
                        e.preventDefault();
                        navigateTo(currentIndex + 1);
                    }
                    break;
            }
        });
    }
    
    // Bind commentary toggle functionality
    function bindCommentaryToggles() {
        var commentaryToggles = document.querySelectorAll('.lexi-commentary-toggle');
        commentaryToggles.forEach(function(toggle) {
            // Check if already bound to prevent duplicates
            if (!toggle.hasAttribute('data-lexi-bound')) {
                toggle.addEventListener('click', handleCommentaryToggle);
                toggle.setAttribute('data-lexi-bound', 'true');
            }
        });
    }
    
    // Handle commentary toggle click
    function handleCommentaryToggle() {
        var isExpanded = this.getAttribute('aria-expanded') === 'true';
        var targetId = this.getAttribute('aria-controls');
        var targetContent = document.getElementById(targetId);
        
        if (targetContent) {
            // Toggle aria-expanded
            this.setAttribute('aria-expanded', !isExpanded);
            // Toggle aria-hidden on content
            targetContent.setAttribute('aria-hidden', isExpanded);
            // Update button text using i18n strings
            this.textContent = isExpanded ? (i18n.showCommentary || 'Show Commentary') : (i18n.hideCommentary || 'Hide Commentary');
        }
    }
    
    // Navigate to specific section
    function navigateTo(index, updateHistory) {
        if (isNavigating || index < 0 || index >= totalSections) return;
        
        isNavigating = true;
        currentIndex = index;
        
        // Update URL with history
        if (updateHistory !== false) {
            updateUrl(index);
        }
        
        // Load section content
        loadSection(index);
    }
    
    // Load section content
    function loadSection(index, updateHistory) {
        // Check cache first
        if (cache[index]) {
            updateContent(cache[index]);
            updateNavigation();
            isNavigating = false;
            return;
        }
        
        // Show loading state
        if (contentBody) {
            contentBody.innerHTML = '<div class="lexi-loading" aria-live="polite">' + (i18n.loading || 'Loading...') + '</div>';
        }
        
        // If REST configuration is missing, try switching sections client-side
        if (!restUrl || !postId) {
            var ssrSection = document.querySelector('.lexi-section[data-section-index="' + index + '"]');
            if (ssrSection) {
                // When sections are pre-rendered (manual/preload), show only target section
                var allSections = document.querySelectorAll('.lexi-section');
                allSections.forEach(function(s) { s.style.display = 'none'; });
                ssrSection.style.display = '';
                updateNavigation();
                isNavigating = false;
                return;
            }
        }

        // Fetch from API
        var url = restUrl + '?post=' + postId + '&index=' + index;
        
        fetch(url)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                if (data.html) {
                    // Cache the response
                    cache[index] = data.html;
                    
                    // Update content
                    updateContent(data.html);
                    
                    // Update current index if different
                    if (data.index !== index) {
                        currentIndex = data.index;
                    }
                } else {
                    throw new Error('No HTML content received');
                }
            })
            .catch(function(error) {
                console.error('Error loading section:', error);
                if (contentBody) {
                    contentBody.innerHTML = '<div class="lexi-error" aria-live="polite">' + (i18n.error || 'Error loading section') + '</div>';
                }
            })
            .finally(function() {
                updateNavigation();
                isNavigating = false;
            });
    }
    
    // Update content and focus
    function updateContent(html) {
        if (!contentBody) return;
        
        contentBody.innerHTML = html;
        
        // Bind commentary toggles for newly loaded content
        bindCommentaryToggles();
        
        // Move focus to main heading
        var heading = contentBody.querySelector('h1, h2, h3');
        if (heading) {
            heading.focus();
        }
        
        // Announce to screen readers
        announceToScreenReader((i18n.section || 'Section') + ' ' + (currentIndex + 1) + ' ' + (i18n.loaded || 'loaded'));
    }
    
    // Update navigation state
    function updateNavigation() {
        // Update TOC active state
        tocLinks.forEach(function(link) {
            var index = parseInt(link.dataset.index, 10);
            link.classList.toggle('active', index === currentIndex);
            link.setAttribute('aria-current', index === currentIndex ? 'page' : 'false');
        });
        
        // Update prev/next buttons
        if (prevBtn) {
            prevBtn.disabled = currentIndex <= 0;
            prevBtn.setAttribute('aria-label', (i18n.previous || 'Previous') + ' ' + (i18n.section || 'Section'));
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentIndex >= totalSections - 1;
            nextBtn.setAttribute('aria-label', (i18n.next || 'Next') + ' ' + (i18n.section || 'Section'));
        }
        
        // Update jump select
        if (jumpSelect) {
            jumpSelect.value = currentIndex;
        }
    }
    
    // Update URL with history
    function updateUrl(index) {
        var url = new URL(window.location);
        url.searchParams.set('sec', index);
        history.pushState({ sectionIndex: index }, '', url);
    }
    
    // Prefetch next section
    function prefetchNext() {
        if (currentIndex < totalSections - 1 && !cache[currentIndex + 1]) {
            var url = restUrl + '?post=' + postId + '&index=' + (currentIndex + 1);
            fetch(url)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.html) {
                        cache[currentIndex + 1] = data.html;
                    }
                })
                .catch(function(error) {
                    console.warn('Failed to prefetch section', currentIndex + 1, error);
                });
        }
    }
    
    
    // Print current section
    function printCurrentSection() {
        var currentSection = contentBody.querySelector('.lexi-section[data-section-index="' + currentIndex + '"]');
        if (!currentSection) {
            announceToScreenReader(i18n.error || 'Error: Section not found');
            return;
        }

        // Create a new window for printing
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        if (!printWindow) {
            announceToScreenReader(i18n.error || 'Error: Could not open print window');
            return;
        }

        // Get the document title and current section title
        var docTitle = document.title;
        var sectionTitle = currentSection.querySelector('h1, h2, h3, h4, h5, h6');
        var sectionTitleText = sectionTitle ? sectionTitle.textContent : 'Section ' + (currentIndex + 1);

        // Build print content
        var printContent = '<!DOCTYPE html><html><head><title>' + 
            escapeHtml(docTitle + ' - ' + sectionTitleText) + 
            '</title><style>' +
            'body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }' +
            'h1, h2, h3, h4, h5, h6 { color: #333; margin-top: 0; }' +
            '.lexi-section { margin-bottom: 20px; }' +
            '.lexi-commentary-section { margin-top: 15px; padding: 10px; background: #f8f9fa; border-left: 3px solid #007cba; }' +
            '.lexi-commentary-toggle { display: none; }' +
            '.lexi-commentary-content { display: block !important; }' +
            '@media print { body { margin: 0; } }' +
            '</style></head><body>' +
            '<h1>' + escapeHtml(sectionTitleText) + '</h1>' +
            currentSection.innerHTML +
            '</body></html>';

        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load, then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };

        announceToScreenReader(i18n.printSuccess || 'Print dialog opened');
    }

    // Copy citation to clipboard
    function copyCitationToClipboard() {
        var currentSection = contentBody.querySelector('.lexi-section[data-section-index="' + currentIndex + '"]');
        if (!currentSection) {
            announceToScreenReader(i18n.error || 'Error: Section not found');
            return;
        }

        // Get section title
        var sectionTitle = currentSection.querySelector('h1, h2, h3, h4, h5, h6');
        var sectionTitleText = sectionTitle ? sectionTitle.textContent : 'Section ' + (currentIndex + 1);

        // Get document title and URL
        var docTitle = document.title;
        var docUrl = window.location.href;

        // Build citation text
        var citation = sectionTitleText + '. ' + docTitle + '. Available at: ' + docUrl + ' (Accessed: ' + new Date().toLocaleDateString() + ')';

        // Copy to clipboard
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(citation).then(function() {
                announceToScreenReader(i18n.citationCopied || 'Citation copied to clipboard');
                showTemporaryMessage(i18n.citationCopied || 'Citation copied!');
            }).catch(function(err) {
                console.error('Failed to copy citation: ', err);
                fallbackCopyToClipboard(citation);
            });
        } else {
            fallbackCopyToClipboard(citation);
        }
    }

    // Fallback copy method for older browsers
    function fallbackCopyToClipboard(text) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                announceToScreenReader(i18n.citationCopied || 'Citation copied to clipboard');
                showTemporaryMessage(i18n.citationCopied || 'Citation copied!');
            } else {
                announceToScreenReader(i18n.error || 'Error: Could not copy citation');
            }
        } catch (err) {
            console.error('Fallback copy failed: ', err);
            announceToScreenReader(i18n.error || 'Error: Could not copy citation');
        }
        
        document.body.removeChild(textArea);
    }

    // Show temporary message
    function showTemporaryMessage(message) {
        var messageEl = document.createElement('div');
        messageEl.className = 'lexi-temp-message';
        messageEl.textContent = message;
        messageEl.style.cssText = 
            'position: fixed; top: 20px; right: 20px; background: #28a745; color: white; ' +
            'padding: 10px 15px; border-radius: 4px; z-index: 10000; font-size: 14px; ' +
            'box-shadow: 0 2px 10px rgba(0,0,0,0.2); transition: opacity 0.3s ease;';
        
        document.body.appendChild(messageEl);
        
        // Fade out after 3 seconds
        setTimeout(function() {
            messageEl.style.opacity = '0';
            setTimeout(function() {
                if (messageEl.parentNode) {
                    messageEl.parentNode.removeChild(messageEl);
                }
            }, 300);
        }, 3000);
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Screen reader announcement
    function announceToScreenReader(message) {
        var announcerId = 'lexi-announcer-' + postId;
        var announcer = document.getElementById(announcerId);
        if (!announcer) {
            announcer = document.createElement('div');
            announcer.id = announcerId;
            announcer.setAttribute('aria-live', 'polite');
            announcer.setAttribute('aria-atomic', 'true');
            announcer.style.position = 'absolute';
            announcer.style.left = '-10000px';
            announcer.style.width = '1px';
            announcer.style.height = '1px';
            announcer.style.overflow = 'hidden';
            document.body.appendChild(announcer);
        }
        announcer.textContent = message;
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Also initialize commentary toggles immediately for Elementor editor
    // This ensures they work even if the main init doesn't run
    bindCommentaryToggles();
    
    // Watch for dynamically added commentary toggles (Elementor editor)
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function(mutations) {
            var shouldRebind = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.classList && node.classList.contains('lexi-commentary-toggle')) {
                                shouldRebind = true;
                            } else if (node.querySelector && node.querySelector('.lexi-commentary-toggle')) {
                                shouldRebind = true;
                            }
                        }
                    });
                }
            });
            if (shouldRebind) {
                bindCommentaryToggles();
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
})();
