<style>
    /* Equal height and precision container layout for dashboard widgets */
    .fi-wi-chart,
    .fi-wi-table {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .fi-wi-chart > .fi-section,
    .fi-wi-table > .fi-section {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 0% !important;
        border-radius: 0.75rem !important;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .fi-wi-chart > .fi-section:hover,
    .fi-wi-table > .fi-section:hover {
        box-shadow: 0 4px 20px -2px rgba(47, 91, 255, 0.08), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
    }

    .fi-wi-chart .fi-section-header,
    .fi-wi-table .fi-section-header {
        min-height: 4.25rem !important;
        display: flex !important;
        align-items: flex-start !important;
        justify-content: space-between !important;
        gap: 1rem !important;
    }

    .fi-wi-chart .fi-section-header-heading,
    .fi-wi-table .fi-section-header-heading {
        font-size: 1.05rem !important;
        font-weight: 600 !important;
        letter-spacing: -0.01em !important;
        line-height: 1.35 !important;
    }

    .fi-wi-chart .fi-section-header-description,
    .fi-wi-table .fi-section-header-description {
        font-size: 0.825rem !important;
        line-height: 1.4 !important;
        color: #64748b !important;
        margin-top: 0.25rem !important;
    }

    .fi-wi-chart .fi-section-content-ctn,
    .fi-wi-table .fi-section-content-ctn {
        flex: 1 1 0% !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .fi-wi-chart .fi-section-content {
        flex: 1 1 0% !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        padding-top: 0.5rem !important;
    }

    .fi-wi-chart .fi-wi-chart-frame {
        min-height: 280px !important;
        height: 280px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: relative !important;
    }

    .fi-wi-chart canvas {
        height: 280px !important;
        max-height: 280px !important;
    }

    /* Filter dropdown styling */
    .fi-wi-chart-filter select {
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        border-radius: 0.5rem !important;
        padding: 0.375rem 2rem 0.375rem 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Message attachments card and icon constraints */
    .fi-attachment-card svg {
        width: 14px !important;
        height: 14px !important;
        max-width: 14px !important;
        max-height: 14px !important;
        flex-shrink: 0 !important;
    }

    .fi-attachment-icon-wrapper svg {
        width: 20px !important;
        height: 20px !important;
        max-width: 20px !important;
        max-height: 20px !important;
        flex-shrink: 0 !important;
    }

    /* Database Notifications Slide-Over modern styling */
    .fi-no-notifications .fi-no-notification {
        border-radius: 0.75rem !important;
        margin: 0.45rem 0.6rem !important;
        transition: all 0.2s ease-in-out !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .dark .fi-no-notifications .fi-no-notification {
        border: 1px solid rgba(51, 65, 85, 0.7) !important;
    }

    /* Unread notification item: soft modern card with subtle slate accent */
    .fi-no-notification-unread-ctn .fi-no-notification {
        border-left: 4px solid #64748b !important;
        background-color: rgba(248, 250, 252, 0.9) !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04) !important;
    }

    .dark .fi-no-notification-unread-ctn .fi-no-notification {
        border-left: 4px solid #94a3b8 !important;
        background-color: rgba(30, 41, 59, 0.6) !important;
    }

    /* Read notification item: elegant muted card with emerald accent */
    .fi-no-notification-read-ctn .fi-no-notification {
        border-left: 4px solid #10b981 !important;
        background-color: rgba(240, 253, 244, 0.35) !important;
        opacity: 0.92;
    }

    .dark .fi-no-notification-read-ctn .fi-no-notification {
        border-left: 4px solid #10b981 !important;
        background-color: rgba(6, 78, 59, 0.15) !important;
        opacity: 0.88;
    }

    .fi-no-notifications .fi-no-notification:hover {
        opacity: 1 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important;
    }

    /* Modern Bravo Layout Refinements for Sections & Forms */
    .fi-section {
        border-radius: 0.875rem !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .fi-section-header {
        padding-bottom: 0.75rem !important;
    }

    .fi-section-header-heading {
        font-weight: 600 !important;
        letter-spacing: -0.01em !important;
    }

    /* Form Tabs Pill Polish */
    .fi-tabs {
        gap: 0.5rem !important;
        padding: 0.25rem !important;
        border-radius: 0.75rem !important;
    }

    .fi-tabs-item {
        border-radius: 0.625rem !important;
        font-weight: 500 !important;
        transition: all 0.15s ease-in-out !important;
    }

    /* Repeater Items Card Polish */
    .fi-fo-repeater-item {
        border-radius: 0.75rem !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .fi-fo-repeater-item:hover {
        border-color: rgba(47, 91, 255, 0.3) !important;
    }

    /* Form actions bottom spacing & clean sticky behavior */
    .fi-form-actions {
        margin-top: 1.5rem !important;
        padding-top: 1rem !important;
    }
</style>
