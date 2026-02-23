// Script pour forcer l'application du layout - Version ULTRA ROBUSTE
(function() {
    'use strict';
    
    console.log('🚀 Fix layout script loading...');
    
    // Configuration
    const CONFIG = {
        sidebarWidth: 250,
        topbarHeight: 70,
        mobileBreakpoint: 768,
        retryDelay: 100,
        maxRetries: 10
    };
    
    // Éléments à surveiller
    const ELEMENTS = {
        sidebar: null,
        topbar: null,
        contentWrapper: null,
        containerFluid: null,
        wrapper: null,
        content: null,
        footer: null
    };
    
    // Styles à appliquer
    const STYLES = {
        sidebar: {
            position: 'fixed',
            top: '0',
            left: '0',
            height: '100vh',
            zIndex: '1030',
            width: CONFIG.sidebarWidth + 'px',
            overflowY: 'auto',
            background: 'linear-gradient(180deg, #4e73df 10%, #224abe 100%)',
            transition: 'transform 0.3s ease'
        },
        topbar: {
            position: 'fixed',
            top: '0',
            left: CONFIG.sidebarWidth + 'px',
            right: '0',
            zIndex: '1040',
            height: CONFIG.topbarHeight + 'px',
            backgroundColor: 'white',
            boxShadow: '0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15)',
            borderBottom: '1px solid #e3e6f0'
        },
        content: {
            marginLeft: CONFIG.sidebarWidth + 'px',
            paddingTop: CONFIG.topbarHeight + 'px',
            width: 'calc(100% - ' + CONFIG.sidebarWidth + 'px)',
            minHeight: 'calc(100vh - ' + CONFIG.topbarHeight + 'px)',
            backgroundColor: '#f8f9fc',
            position: 'relative',
            paddingLeft: '0',
            paddingRight: '0',
            marginRight: '0'
        },
        containerFluid: {
            marginLeft: CONFIG.sidebarWidth + 'px',
            paddingTop: CONFIG.topbarHeight + 'px',
            width: 'calc(100% - ' + CONFIG.sidebarWidth + 'px)',
            minHeight: 'calc(100vh - ' + CONFIG.topbarHeight + 'px)',
            backgroundColor: '#f8f9fc',
            position: 'relative',
            marginRight: '0',
            paddingLeft: '1.5rem',
            paddingRight: '1.5rem',
            padding: '1.5rem',
            maxWidth: 'none'
        },
        footer: {
            marginLeft: CONFIG.sidebarWidth + 'px',
            width: 'calc(100% - ' + CONFIG.sidebarWidth + 'px)',
            backgroundColor: 'white',
            borderTop: '1px solid #e3e6f0'
        },
        mobile: {
            sidebar: {
                transform: 'translateX(-100%)'
            },
            topbar: {
                left: '0',
                width: '100%'
            },
            content: {
                marginLeft: '0',
                width: '100%',
                paddingLeft: '1rem',
                paddingRight: '1rem'
            },
            containerFluid: {
                marginLeft: '0',
                width: '100%',
                paddingLeft: '1rem',
                paddingRight: '1rem'
            },
            footer: {
                marginLeft: '0',
                width: '100%'
            }
        }
    };
    
    // Fonction pour trouver les éléments
    function findElements() {
        ELEMENTS.sidebar = document.getElementById('accordionSidebar') || 
                          document.querySelector('.sidebar') ||
                          document.querySelector('[id*="sidebar"]');
        
        ELEMENTS.topbar = document.getElementById('topbar') || 
                         document.querySelector('.topbar') ||
                         document.querySelector('.navbar') ||
                         document.querySelector('[id*="topbar"]');
        
        ELEMENTS.contentWrapper = document.getElementById('content-wrapper') || 
                                 document.getElementById('content') ||
                                 document.querySelector('.main-content');
        
        ELEMENTS.containerFluid = document.querySelector('.container-fluid');
        ELEMENTS.wrapper = document.getElementById('wrapper');
        ELEMENTS.content = document.getElementById('content');
        ELEMENTS.footer = document.querySelector('footer.sticky-footer');
        
        console.log('🔍 Elements found:', {
            sidebar: !!ELEMENTS.sidebar,
            topbar: !!ELEMENTS.topbar,
            contentWrapper: !!ELEMENTS.contentWrapper,
            containerFluid: !!ELEMENTS.containerFluid,
            wrapper: !!ELEMENTS.wrapper,
            content: !!ELEMENTS.content,
            footer: !!ELEMENTS.footer
        });
    }
    
    // Fonction pour appliquer les styles
    function applyStyles(element, styles) {
        if (!element) return;
        
        Object.keys(styles).forEach(property => {
            element.style.setProperty(property, styles[property], 'important');
        });
    }
    
    // Fonction pour forcer le layout
    function forceLayout() {
        console.log('🎯 Applying layout fixes...');
        
        const isMobile = window.innerWidth <= CONFIG.mobileBreakpoint;
        
        // Appliquer les styles de base
        if (ELEMENTS.sidebar) {
            const sidebarStyles = isMobile ? { ...STYLES.sidebar, ...STYLES.mobile.sidebar } : STYLES.sidebar;
            applyStyles(ELEMENTS.sidebar, sidebarStyles);
            console.log('✅ Sidebar fixed');
        }
        
        if (ELEMENTS.topbar) {
            const topbarStyles = isMobile ? { ...STYLES.topbar, ...STYLES.mobile.topbar } : STYLES.topbar;
            applyStyles(ELEMENTS.topbar, topbarStyles);
            console.log('✅ Topbar fixed');
        }
        
        if (ELEMENTS.contentWrapper) {
            const contentStyles = isMobile ? { ...STYLES.content, ...STYLES.mobile.content } : STYLES.content;
            applyStyles(ELEMENTS.contentWrapper, contentStyles);
            console.log('✅ Content wrapper fixed');
        }
        
        if (ELEMENTS.containerFluid) {
            const containerStyles = isMobile ? { ...STYLES.containerFluid, ...STYLES.mobile.containerFluid } : STYLES.containerFluid;
            applyStyles(ELEMENTS.containerFluid, containerStyles);
            console.log('✅ Container fluid fixed');
        }
        
        if (ELEMENTS.footer) {
            const footerStyles = isMobile ? { ...STYLES.footer, ...STYLES.mobile.footer } : STYLES.footer;
            applyStyles(ELEMENTS.footer, footerStyles);
            console.log('✅ Footer fixed');
        }
        
        // Fix pour le wrapper principal
        if (ELEMENTS.wrapper) {
            applyStyles(ELEMENTS.wrapper, {
                marginLeft: '0',
                width: '100%',
                overflowX: 'hidden'
            });
        }
        
        // Fix pour le contenu spécifique
        if (ELEMENTS.content) {
            const contentStyles = isMobile ? { ...STYLES.content, ...STYLES.mobile.content } : STYLES.content;
            applyStyles(ELEMENTS.content, contentStyles);
        }
        
        // Fix pour les tables
        const tables = document.querySelectorAll('.table-responsive');
        tables.forEach(table => {
            applyStyles(table, {
                overflowX: 'auto',
                border: '1px solid #e3e6f0',
                borderRadius: '0.35rem',
                width: '100%'
            });
        });
        
        // Fix pour les cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            applyStyles(card, {
                marginBottom: '1.5rem',
                border: '1px solid #e3e6f0',
                boxShadow: '0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15)',
                borderRadius: '0.35rem',
                width: '100%'
            });
        });
        
        // Fix pour les card-body (IMPORTANT !)
        const cardBodies = document.querySelectorAll('.card-body');
        cardBodies.forEach(cardBody => {
            applyStyles(cardBody, {
                width: '100%',
                padding: '1.25rem',
                flex: '1 1 auto',
                boxSizing: 'border-box'
            });
        });
        
        // Fix pour les card-header
        const cardHeaders = document.querySelectorAll('.card-header');
        cardHeaders.forEach(cardHeader => {
            applyStyles(cardHeader, {
                width: '100%'
            });
        });
        
        // Fix pour les card-footer
        const cardFooters = document.querySelectorAll('.card-footer');
        cardFooters.forEach(cardFooter => {
            applyStyles(cardFooter, {
                width: '100%'
            });
        });
        
        // Fix pour les rows Bootstrap
        const rows = document.querySelectorAll('.row');
        rows.forEach(row => {
            applyStyles(row, {
                marginLeft: '0',
                marginRight: '0',
                width: '100%'
            });
        });
        
        // Fix pour les DataTables
        const dataTablesWrappers = document.querySelectorAll('.dataTables_wrapper');
        dataTablesWrappers.forEach(wrapper => {
            applyStyles(wrapper, {
                width: '100%'
            });
        });
        
        // Fix pour les alertes
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            applyStyles(alert, {
                width: '100%'
            });
        });
        
        // Fix pour les sections de contenu
        const contentSections = document.querySelectorAll('.content-section, [class*="section"]');
        contentSections.forEach(section => {
            applyStyles(section, {
                width: '100%',
                marginBottom: '2rem'
            });
        });
        
        // Fix pour les chartes
        const chartContainers = document.querySelectorAll('.chart-container, canvas');
        chartContainers.forEach(chart => {
            applyStyles(chart, {
                width: '100%',
                height: 'auto'
            });
        });
        
        // Fix pour les formulaires
        const forms = document.querySelectorAll('.form-group, .form-row, .search-form');
        forms.forEach(form => {
            applyStyles(form, {
                width: '100%'
            });
        });
        
        // Fix pour les boutons d'action
        const actionButtons = document.querySelectorAll('.action-buttons, .export-buttons');
        actionButtons.forEach(buttons => {
            applyStyles(buttons, {
                width: '100%',
                textAlign: 'right',
                marginBottom: '1rem'
            });
        });
        
        // Fix pour les messages et notifications
        const messages = document.querySelectorAll('.message-container, .notification-container, .stock-alert');
        messages.forEach(message => {
            applyStyles(message, {
                width: '100%',
                marginBottom: '1rem'
            });
        });
        
        // Fix pour les modals
        const modals = document.querySelectorAll('.modal-content, .modal-body, .modal-header, .modal-footer');
        modals.forEach(modal => {
            applyStyles(modal, {
                width: '100%'
            });
        });
        
        console.log('🎉 Layout fixes applied successfully');
    }
    
    // Fonction pour vérifier si le layout est correct
    function isLayoutCorrect() {
        if (!ELEMENTS.sidebar || !ELEMENTS.topbar) {
            return false;
        }
        
        const sidebarStyle = window.getComputedStyle(ELEMENTS.sidebar);
        const topbarStyle = window.getComputedStyle(ELEMENTS.topbar);
        
        // Vérifier si le contenu principal existe
        const mainContent = ELEMENTS.contentWrapper || ELEMENTS.containerFluid;
        if (!mainContent) return false;
        
        const contentStyle = window.getComputedStyle(mainContent);
        
        return sidebarStyle.position === 'fixed' && 
               topbarStyle.position === 'fixed' && 
               contentStyle.marginLeft === CONFIG.sidebarWidth + 'px' &&
               contentStyle.width === 'calc(100% - ' + CONFIG.sidebarWidth + 'px)';
    }
    
    // Fonction pour appliquer le layout avec retry
    function applyLayoutWithRetry(retryCount = 0) {
        findElements();
        forceLayout();
        
        if (!isLayoutCorrect() && retryCount < CONFIG.maxRetries) {
            console.log(`🔄 Layout not correct, retrying... (${retryCount + 1}/${CONFIG.maxRetries})`);
            setTimeout(() => applyLayoutWithRetry(retryCount + 1), CONFIG.retryDelay);
        } else if (retryCount >= CONFIG.maxRetries) {
            console.warn('⚠️ Max retries reached, layout may not be perfect');
        } else {
            console.log('✅ Layout applied successfully');
        }
    }
    
    // Initialisation
    function init() {
        console.log('🚀 Initializing fix layout...');
        
        // Appliquer immédiatement
        applyLayoutWithRetry();
        
        // Réappliquer après un délai pour s'assurer que tout est chargé
        setTimeout(applyLayoutWithRetry, 100);
        setTimeout(applyLayoutWithRetry, 500);
        setTimeout(applyLayoutWithRetry, 1000);
        
        // Réappliquer lors du redimensionnement
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                console.log('📱 Window resized, reapplying layout...');
                applyLayoutWithRetry();
            }, 150);
        });
        
        // Observer les changements du DOM
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                let shouldReapply = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' || mutation.type === 'attributes') {
                        shouldReapply = true;
                    }
                });
                
                if (shouldReapply) {
                    console.log('🔄 DOM changed, checking layout...');
                    setTimeout(applyLayoutWithRetry, 100);
                }
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['style', 'class']
            });
        }
        
        // Fix pour les DataTables
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $(document).on('init.dt', function() {
                console.log('📊 DataTable initialized, fixing layout...');
                setTimeout(applyLayoutWithRetry, 100);
            });
        }
        
        // Fix pour les modals Bootstrap
        if (typeof $ !== 'undefined') {
            $(document).on('shown.bs.modal', function() {
                console.log('🔍 Modal shown, fixing layout...');
                setTimeout(applyLayoutWithRetry, 100);
            });
        }
        
        console.log('✅ Fix layout script initialized');
    }
    
    // Démarrer quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Exposer la fonction pour un usage manuel
    window.forceLayoutFix = applyLayoutWithRetry;
    
})(); 