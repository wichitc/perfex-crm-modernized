/**
 * Assets Management Module - JavaScript
 * Version 1.2.0
 */

(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        initAssetModule();
    });

    function initAssetModule() {
        // Initialize select pickers
        initSelectPickers();
        
        // Initialize date pickers
        initDatePickers();
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initialize currency inputs
        initCurrencyInputs();
        
        // Initialize barcode scanner
        initBarcodeScanner();
        
        // Initialize notification dropdown
        initNotifications();
    }

    function initSelectPickers() {
        $('.selectpicker').selectpicker({
            liveSearch: true,
            noneSelectedText: 'Select an option'
        });
    }

    function initDatePickers() {
        if (typeof init_datepicker === 'function') {
            init_datepicker();
        }
    }

    function initCurrencyInputs() {
        $('input[data-type="currency"]').on('blur', function() {
            var value = $(this).val();
            if (value) {
                value = value.replace(/[^0-9.-]+/g, '');
                $(this).val(parseFloat(value).toFixed(2));
            }
        });
    }

    function initBarcodeScanner() {
        // Handle barcode input (from scanner)
        var barcodeBuffer = '';
        var barcodeTimeout;

        $(document).on('keypress', function(e) {
            // Check if we're in an input field
            if ($(e.target).is('input, textarea')) {
                return;
            }

            // Build barcode string
            barcodeBuffer += String.fromCharCode(e.which);
            
            clearTimeout(barcodeTimeout);
            barcodeTimeout = setTimeout(function() {
                if (barcodeBuffer.length >= 4) {
                    searchAssetByBarcode(barcodeBuffer);
                }
                barcodeBuffer = '';
            }, 100);
        });
    }

    function searchAssetByBarcode(code) {
        $.ajax({
            url: admin_url + 'assets/scan_barcode',
            type: 'POST',
            data: { code: code },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.asset) {
                    window.location.href = admin_url + 'assets/manage_assets#' + response.asset.id;
                } else {
                    alert_float('warning', response.message || 'Asset not found');
                }
            }
        });
    }

    function initNotifications() {
        // Load notifications badge
        loadNotificationCount();
        
        // Refresh every 5 minutes
        setInterval(loadNotificationCount, 300000);
    }

    function loadNotificationCount() {
        if (typeof admin_url !== 'undefined') {
            $.get(admin_url + 'assets/get_notifications', function(data) {
                var notifications = JSON.parse(data);
                var unread = notifications.filter(function(n) { return !n.is_read; }).length;
                
                if (unread > 0) {
                    $('.asset-notification-badge').text(unread).show();
                } else {
                    $('.asset-notification-badge').hide();
                }
            });
        }
    }

    // Global functions for use in views
    window.AssetModule = {
        // Refresh a DataTable
        refreshTable: function(tableClass) {
            var table = $(tableClass).DataTable();
            if (table) {
                table.ajax.reload(null, false);
            }
        },

        // Show asset preview
        showAssetPreview: function(assetId) {
            $.get(admin_url + 'assets/get_asset_data_ajax/' + assetId, function(html) {
                $('#asset_sm_view').html(html).removeClass('hide');
            });
        },

        // Generate barcode for asset
        generateBarcode: function(assetId) {
            window.location.href = admin_url + 'assets/generate_barcode/' + assetId;
        },

        // Generate QR code for asset
        generateQRCode: function(assetId) {
            window.location.href = admin_url + 'assets/generate_qr_code/' + assetId;
        },

        // Print asset labels
        printLabels: function(assetIds) {
            if (!Array.isArray(assetIds)) {
                assetIds = [assetIds];
            }
            window.open(admin_url + 'assets/print_labels?ids=' + assetIds.join(','), '_blank');
        },

        // Quick checkout
        quickCheckout: function(assetId) {
            $('#checkout_asset_id').val(assetId).selectpicker('refresh');
            $('#checkoutModal').modal('show');
        },

        // Quick reserve
        quickReserve: function(assetId) {
            $('#reservation_asset_id').val(assetId).selectpicker('refresh');
            $('#reservationModal').modal('show');
        },

        // Mark notification as read
        markNotificationRead: function(id) {
            $.post(admin_url + 'assets/mark_notification_read/' + id, function() {
                loadNotificationCount();
            });
        },

        // Mark all notifications as read
        markAllNotificationsRead: function() {
            $.post(admin_url + 'assets/mark_all_notifications_read', function() {
                loadNotificationCount();
                $('.notification-item').removeClass('unread');
            });
        }
    };

    // Asset form validation rules
    window.assetValidationRules = {
        assets_name: 'required',
        amount: { required: true, min: 1 },
        unit: 'required',
        date_buy: 'required',
        warranty_period: { required: true, min: 0 },
        unit_price: 'required',
        depreciation: { required: true, min: 0 },
        assets_code: {
            required: true,
            remote: {
                url: admin_url + 'assets/assets_code_exists',
                type: 'post',
                data: {
                    assets_code: function() {
                        return $('input[name="assets_code"]').val();
                    },
                    id: function() {
                        return $('input[name="id"]').val();
                    }
                }
            }
        }
    };

    // Small table view toggle
    window.toggle_small_view_asset = function(table, main_data) {
        $("body").toggleClass('small-table');
        var tablewrap = $('#small-table');
        if (tablewrap.length === 0) return;
        
        var _visible = false;
        if (tablewrap.hasClass('col-md-5')) {
            tablewrap.removeClass('col-md-5').addClass('col-md-12');
            _visible = true;
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
        } else {
            tablewrap.addClass('col-md-5').removeClass('col-md-12');
            $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
        }
        
        var _table = $(table).DataTable();
        var hidden_columns = [2, 3, 6, 7, 8];
        _table.columns(hidden_columns).visible(_visible, false);
        _table.columns.adjust();
        $(main_data).toggleClass('hide');
        $(window).trigger('resize');
    };

    // Initialize asset preview
    window.init_asset = function(id) {
        load_small_table_item_asset(id, '#asset_sm_view', 'asset_id', 'assets/get_asset_data_ajax', '.asset_sm');
    };

    window.load_small_table_item_asset = function(pr_id, selector, input_name, url, table) {
        var _tmpID = $('input[name="' + input_name + '"]').val();
        
        if (_tmpID !== '' && !window.location.hash) {
            pr_id = _tmpID;
            $('input[name="' + input_name + '"]').val('');
        } else {
            if (window.location.hash && !pr_id) {
                pr_id = window.location.hash.substring(1);
            }
        }
        
        if (typeof(pr_id) == 'undefined' || pr_id === '') return;
        if (!$("body").hasClass('small-table')) {
            toggle_small_view_asset(table, selector);
        }
        
        $('input[name="' + input_name + '"]').val(pr_id);
        do_hash_helper(pr_id);
        $(selector).load(admin_url + url + '/' + pr_id);
        
        if (is_mobile()) {
            $('html, body').animate({
                scrollTop: $(selector).offset().top + 150
            }, 600);
        }
    };

})(jQuery);
