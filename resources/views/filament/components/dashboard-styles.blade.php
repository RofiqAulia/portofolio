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
</style>
