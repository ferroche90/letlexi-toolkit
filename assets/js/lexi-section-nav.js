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
    var fontIncBtn = document.querySelector('.lexi-font__inc');
    var fontDecBtn = document.querySelector('.lexi-font__dec');
    var fontResetBtn = document.querySelector('.lexi-font__reset');
    var tocToggle = document.querySelector('.lexi-toc__toggle');
    var tocList = document.querySelector('.lexi-toc__list');
    
    // Initialize on DOM ready
    function init() {
        if (!docWrapper || !contentBody) return;
        
        // Read initial section from URL
        var initialIndex = getInitialSectionIndex();
        
        // Set up event listeners
        bindEvents();
        
        // Load initial section if not already rendered
        if (initialIndex >= 0 && !hasContent()) {
            navigateTo(initialIndex, false);
        } else {
            updateNavigation();
        }
        
        // Prefetch next section after idle
        if ('requestIdleCallback' in window) {
            requestIdleCallback(prefetchNext);
        }
        
        // Restore font scale from localStorage
        restoreFontScale();
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
        var hashMatch = hash.match(/#sec-(\d+)/);
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
        
        // Font size controls
        if (fontIncBtn) {
            fontIncBtn.addEventListener('click', function() {
                adjustFontSize(2);
            });
        }
        
        if (fontDecBtn) {
            fontDecBtn.addEventListener('click', function() {
                adjustFontSize(-2);
            });
        }
        
        if (fontResetBtn) {
            fontResetBtn.addEventListener('click', function() {
                resetFontSize();
            });
        }
        
        // TOC toggle for mobile
        if (tocToggle && tocList) {
            tocToggle.addEventListener('click', function() {
                var isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                docWrapper.classList.toggle('toc-expanded', !isExpanded);
            });
        }
        
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
    
    // Font size adjustment
    function adjustFontSize(delta) {
        var currentSize = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--lexi-font-scale') || '1');
        var newSize = Math.max(0.75, Math.min(1.5, currentSize + (delta / 100)));
        
        document.documentElement.style.setProperty('--lexi-font-scale', newSize);
        localStorage.setItem('lexi-font-scale', newSize);
    }
    
    // Reset font size
    function resetFontSize() {
        document.documentElement.style.setProperty('--lexi-font-scale', '1');
        localStorage.setItem('lexi-font-scale', '1');
    }
    
    // Restore font scale from localStorage
    function restoreFontScale() {
        var savedScale = localStorage.getItem('lexi-font-scale');
        if (savedScale) {
            document.documentElement.style.setProperty('--lexi-font-scale', savedScale);
        }
    }
    
    // Screen reader announcement
    function announceToScreenReader(message) {
        var announcer = document.getElementById('lexi-announcer');
        if (!announcer) {
            announcer = document.createElement('div');
            announcer.id = 'lexi-announcer';
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
})();
