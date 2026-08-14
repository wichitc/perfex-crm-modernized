<?php
defined('BASEPATH') or exit('No direct script access allowed');
init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => 'Kitchen Sink',
            'subtitle' => 'Every UI primitive in every state. Internal QA aid — not linked from the menu.',
            'icon'     => 'fa fa-flask',
            'crumbs'   => [
                ['label' => 'WooCommerce',  'url' => admin_url('woocommerce')],
                ['label' => 'Kitchen Sink', 'url' => null],
            ],
        ]); ?>

        <!-- ============================================== status pills -->
        <div class="woo-card">
            <div class="woo-card__header">
                <h3 class="woo-card__title">Status pills</h3>
            </div>
            <p class="text-muted">Color-coded; dot prefix for color-blind redundancy. Drops the dot below 375px.</p>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                <?php foreach ([
                    'processing', 'completed', 'on-hold',
                    'failed', 'cancelled', 'refunded',
                    'guest', 'paid', 'draft',
                    'wc-pos-completed', // unknown → neutral
                ] as $status): ?>
                    <?php $this->load->view('components/_status_pill', ['status' => $status]); ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ============================================== badges -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Badges</h3></div>
            <div style="display: flex; gap: 8px;">
                <span class="woo-badge">12</span>
                <span class="woo-badge">v3.0.0-dev</span>
                <span class="woo-badge">Beta</span>
            </div>
        </div>

        <!-- ============================================== banners -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Banners</h3></div>
            <?php $this->load->view('components/_banner', [
                'severity' => 'info',
                'icon'     => 'fa fa-info-circle',
                'title'    => 'Guest customer.',
                'body'     => 'A placeholder client will be created on conversion.',
            ]); ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'warn',
                'icon'     => 'fa fa-exclamation-triangle',
                'title'    => 'Tax adjustment:',
                'body'     => 'Total tax has been added as an adjustment line because Perfex does not model variant tax formats.',
            ]); ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'success',
                'icon'     => 'fa fa-check-circle',
                'body'     => 'Webhooks generated and verified.',
            ]); ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'danger',
                'icon'     => 'fa fa-times-circle',
                'title'    => 'Test connection failed:',
                'body'     => 'The store URL responded 401 — check the consumer key/secret.',
            ]); ?>
        </div>

        <!-- ============================================== empty states -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Empty states</h3></div>
            <?php $this->load->view('components/_empty_state', [
                'icon'   => 'fa fa-shopping-bag',
                'title'  => 'No stores connected',
                'body'   => 'Connect your first WooCommerce store to start syncing products, customers, and orders.',
                'action' => [
                    'label' => '+ Connect first store',
                    'attrs' => ['class' => 'btn btn-primary'],
                ],
            ]); ?>
        </div>

        <!-- ============================================== skeletons -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Loading skeletons</h3></div>
            <p class="text-muted">Shown for 200ms+ data fetches.</p>
            <?php $this->load->view('components/_skeleton', ['variant' => 'title']); ?>
            <?php $this->load->view('components/_skeleton', ['variant' => 'row', 'rows' => 4]); ?>
            <hr>
            <div style="display: flex; gap: 16px; align-items: center; margin-top: 16px;">
                <?php $this->load->view('components/_skeleton', ['variant' => 'avatar']); ?>
                <div style="flex: 1">
                    <?php $this->load->view('components/_skeleton', ['variant' => 'row', 'rows' => 2]); ?>
                </div>
            </div>
        </div>

        <!-- ============================================== meta grid -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Meta grid</h3></div>
            <div class="woo-meta-grid">
                <div class="woo-meta-grid__key">Order number</div>
                <div class="woo-meta-grid__val">WC-1042</div>

                <div class="woo-meta-grid__key">Status</div>
                <div class="woo-meta-grid__val">
                    <?php $this->load->view('components/_status_pill', ['status' => 'completed']); ?>
                </div>

                <div class="woo-meta-grid__key">Total</div>
                <div class="woo-meta-grid__val">$129.50 USD</div>

                <div class="woo-meta-grid__key">Customer</div>
                <div class="woo-meta-grid__val">guest@example.test
                    <?php $this->load->view('components/_status_pill', ['status' => 'guest']); ?>
                </div>

                <div class="woo-meta-grid__key">Date paid</div>
                <div class="woo-meta-grid__val">2026-04-27 12:34:56</div>
            </div>
        </div>

        <!-- ============================================== action drawer -->
        <div class="row">
            <div class="col-md-8">
                <div class="woo-card">
                    <div class="woo-card__header"><h3 class="woo-card__title">Action drawer (left + right pane preview)</h3></div>
                    <p class="text-muted">Detail screens use this layout: data on the left, actions on the right.</p>
                    <?php $this->load->view('components/_skeleton', ['variant' => 'card']); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="woo-action-drawer">
                    <div class="woo-action-drawer__group">
                        <h4 class="woo-action-drawer__heading">Status</h4>
                        <button class="btn btn-default woo-action-drawer__action">Update status…</button>
                        <button class="btn btn-default woo-action-drawer__action">Refresh</button>
                    </div>
                    <div class="woo-action-drawer__group">
                        <h4 class="woo-action-drawer__heading">Conversion</h4>
                        <button class="btn btn-primary woo-action-drawer__action">Convert to invoice</button>
                    </div>
                    <div class="woo-action-drawer__group">
                        <h4 class="woo-action-drawer__heading">Danger zone</h4>
                        <button class="btn btn-danger woo-action-drawer__action">Delete on Woo</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== toasts -->
        <div class="woo-card">
            <div class="woo-card__header"><h3 class="woo-card__title">Toasts</h3></div>
            <p class="text-muted">Stacked top-right; live preview here.</p>
            <div class="woo-toast-stack" style="position: relative; top: 0; right: 0;">
                <div class="woo-toast woo-toast--success">Mapping saved</div>
                <div class="woo-toast">5 orders refreshed from Woo</div>
                <div class="woo-toast woo-toast--warn">Webhook secret was regenerated — re-issue the Woo webhooks</div>
                <div class="woo-toast woo-toast--danger">Test connection failed: HTTP 401</div>
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
