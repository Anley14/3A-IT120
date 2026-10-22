<?php defined('BASEPATH') OR exit('No direct script access allowed');
$is_employee = $account['role'] === 'employee';
$portal_name = $is_employee ? 'Employee workspace' : 'Customer portal';
$counts = array('New' => 0, 'Contacted' => 0, 'Scheduled' => 0, 'Completed' => 0);
foreach ($requests as $request) {
    if (isset($counts[$request['status']])) {
        $counts[$request['status']]++;
    }
}
$active_count = $counts['New'] + $counts['Contacted'] + $counts['Scheduled'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#4165df">
  <title><?php echo html_escape($portal_name); ?> | Sun Son Solar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
  <style>
    .dashboard{min-height:100vh;background:#f5f8ff;padding:30px 0 70px}.dashboard-wrap{max-width:1120px}.dashboard-top{display:flex;align-items:center;justify-content:space-between;padding-bottom:18px;border-bottom:1px solid #d7e0f0}.dashboard-top form{margin:0}.signout{border:1px solid #b7c9ed;background:#fff;color:#4165df;padding:10px 16px;font-size:12px;font-weight:700;cursor:pointer}.dashboard-main{padding:42px 0}.dashboard-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:28px}.dashboard-tag{display:block;margin-bottom:8px;color:#4165df;font-size:10px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase}.dashboard-heading h1{margin:0;color:#203c63;font:700 clamp(30px,4.5vw,46px)/1.12 'Manrope',sans-serif;letter-spacing:-1.8px}.dashboard-heading p{margin:10px 0 0;color:#7185a4;font-size:13px}.dashboard-heading .button{flex-shrink:0}.dashboard-metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin:26px 0}.metric,.dashboard-panel{border:1px solid #d7e0f0;background:#fff}.metric{padding:20px 22px}.metric-label{color:#7185a4;font-size:11px}.metric-value{display:block;margin-top:5px;color:#203c63;font:700 29px/1 'Manrope',sans-serif}.metric-note{display:block;margin-top:7px;color:#8798b1;font-size:10px}.dashboard-panel{padding:22px;margin-top:18px}.panel-heading{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:15px}.panel-heading h2{margin:0;color:#203c63;font:700 17px 'Manrope',sans-serif}.panel-heading p{margin:3px 0 0;color:#8294ad;font-size:11px}.request-list{display:grid}.request-row{display:grid;grid-template-columns:minmax(115px,.75fr) minmax(130px,1fr) minmax(115px,.75fr) auto;align-items:center;gap:15px;padding:15px 0;border-top:1px solid #e8edf6}.request-row:first-child{border-top:0}.request-primary strong,.request-primary small{display:block}.request-primary strong{color:#203c63;font-size:12px}.request-primary small,.request-meta{color:#8294ad;font-size:10px}.request-service{color:#425e83;font-size:12px;font-weight:700}.status-pill{display:inline-flex;width:max-content;align-items:center;padding:5px 9px;border-radius:999px;background:#edf2ff;color:#4165df;font-size:10px;font-weight:700}.status-contacted{background:#fff7dd;color:#89660a}.status-scheduled{background:#e8f2ff;color:#3157bf}.status-completed{background:#eef1f7;color:#65768d}.empty-state{text-align:center;padding:34px 15px;color:#7185a4}.empty-state strong{display:block;color:#203c63;font:600 16px 'Manrope',sans-serif}.empty-state p{font-size:12px}.empty-state .button{margin-top:8px}.notice{padding:11px 14px;margin:15px 0;border-left:3px solid #4165df;background:#edf2ff;color:#3157bf;font-size:12px}.notice-error{border-left-color:#ffd23f;background:#fff9e4;color:#805a0b}.employee-row{grid-template-columns:minmax(145px,1.2fr) minmax(100px,.7fr) minmax(170px,1.2fr) minmax(120px,.8fr) minmax(145px,.9fr)}.request-contact{min-width:0}.request-contact a,.request-contact span{display:block;overflow-wrap:anywhere;color:#7185a4;font-size:10px}.request-contact a:hover{color:#4165df}.request-details{grid-column:1/-1;margin-top:-5px;color:#7185a4;font-size:11px;line-height:1.6;white-space:pre-wrap}.status-form{display:flex;align-items:center;gap:6px}.status-form select{max-width:110px;border:1px solid #d7e0f0;background:#fff;color:#203c63;padding:7px;font:11px 'DM Sans',sans-serif}.status-form button{border:0;border-radius:3px;background:#4165df;color:#fff;padding:8px 10px;font-size:10px;font-weight:700;cursor:pointer}.employee-guidance{display:flex;justify-content:space-between;gap:20px;margin-top:22px;padding:19px 22px;background:#e9efff;color:#3157bf;font-size:12px}.employee-guidance strong{display:block;color:#203c63;font:700 13px 'Manrope',sans-serif;margin-bottom:4px}.employee-guidance p{margin:0;line-height:1.65}.dashboard-footer{margin-top:30px;color:#8294ad;font-size:10px}.dashboard-footer a{color:#4165df;font-weight:700}.order-row{display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:15px;padding:14px 0;border-top:1px solid #e8edf6}.order-row a{color:#4165df;font-size:11px;font-weight:700}.order-name{color:#203c63;font-size:12px;font-weight:700}.order-sub{display:block;color:#8294ad;font-size:10px;margin-top:4px}.order-status-form{display:flex;gap:6px}.order-status-form select{border:1px solid #d7e0f0;background:#fff;padding:7px;color:#203c63;font-size:10px}.order-status-form button{border:0;background:#4165df;color:#fff;padding:7px 10px;font-size:10px;font-weight:700;cursor:pointer}
    @media(max-width:850px){.employee-row{grid-template-columns:1fr 1fr}.employee-row .request-details{grid-column:1/-1}.employee-row .status-form{grid-column:1/-1}.request-row{grid-template-columns:1fr 1fr}.request-meta{justify-self:end}.dashboard-heading{align-items:flex-start;flex-direction:column}.order-row{grid-template-columns:1fr auto}.order-status-form{grid-column:1/-1}}
    @media(max-width:560px){.dashboard{padding-top:20px}.dashboard-main{padding-top:30px}.dashboard-metrics{grid-template-columns:1fr;gap:9px}.metric{padding:15px 17px}.metric-value{font-size:25px}.request-row,.employee-row{grid-template-columns:1fr;gap:7px}.request-meta{justify-self:start}.employee-row .request-details,.employee-row .status-form{grid-column:1}.dashboard-panel{padding:16px}.employee-guidance{flex-direction:column}}
  </style>
</head>
<body>
  <main class="dashboard">
    <div class="wrap dashboard-wrap">
      <header class="dashboard-top">
        <a class="brand" href="<?php echo base_url(); ?>" aria-label="Sun Son Solar home"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="17" r="6"/><path d="M20 2v5m0 20v6M5 17h6m18 0h6M9.4 6.4 13 10m14 14 3.6 3.6M30.6 6.4 27 10m-14 14-3.6 3.6M7 33h26l-3-7H10z"/></svg></span><span><strong>sun son</strong><small>SOLAR CO.</small></span></a>
        <a class="signout" href="<?php echo site_url('portal'); ?>">Workspace</a><?php echo form_open('auth/logout'); ?><button class="signout" type="submit">Sign out</button></form>
      </header>

      <section class="dashboard-main">
        <?php if ($this->session->flashdata('dashboard_success')): ?><p class="notice" role="status"><?php echo html_escape($this->session->flashdata('dashboard_success')); ?></p><?php endif; ?>
        <?php if ($this->session->flashdata('dashboard_error')): ?><p class="notice notice-error" role="alert"><?php echo html_escape($this->session->flashdata('dashboard_error')); ?></p><?php endif; ?>

        <div class="dashboard-heading">
          <div><span class="dashboard-tag"><?php echo $is_employee ? 'EMPLOYEE WORKSPACE' : 'CUSTOMER PORTAL'; ?></span>
            <h1><?php echo $is_employee ? 'Service overview' : 'Your solar journey'; ?></h1>
            <p><?php echo $is_employee
                ? 'Welcome, ' . html_escape($account['name']) . '. Review customer requests and keep the next steps moving.'
                : 'Welcome back, ' . html_escape($account['name']) . '. Find your requests and service updates here.'; ?></p>
          </div>
          <?php if (!$is_employee): ?><a class="button button-lime" href="<?php echo base_url(); ?>#contact">Request a free quote <span aria-hidden="true">↗</span></a><?php endif; ?>
        </div>

        <section class="dashboard-metrics" aria-label="Request summary">
          <?php if ($is_employee): ?>
            <article class="metric"><span class="metric-label">New requests</span><strong class="metric-value"><?php echo (int) $counts['New']; ?></strong><span class="metric-note">Waiting for first contact</span></article>
            <article class="metric"><span class="metric-label">In progress</span><strong class="metric-value"><?php echo (int) ($counts['Contacted'] + $counts['Scheduled']); ?></strong><span class="metric-note">Contacted or scheduled</span></article>
            <article class="metric"><span class="metric-label">Completed</span><strong class="metric-value"><?php echo (int) $counts['Completed']; ?></strong><span class="metric-note">Completed service requests</span></article>
          <?php else: ?>
            <article class="metric"><span class="metric-label">Your requests</span><strong class="metric-value"><?php echo count($requests); ?></strong><span class="metric-note">Quotes and service requests</span></article>
            <article class="metric"><span class="metric-label">In progress</span><strong class="metric-value"><?php echo (int) $active_count; ?></strong><span class="metric-note">Being reviewed by our team</span></article>
            <article class="metric"><span class="metric-label">Completed</span><strong class="metric-value"><?php echo (int) $counts['Completed']; ?></strong><span class="metric-note">Finished requests</span></article>
          <?php endif; ?>
        </section>

        <section class="dashboard-panel" aria-labelledby="requests-title">
          <div class="panel-heading"><div><h2 id="requests-title"><?php echo $is_employee ? 'Customer request queue' : 'Recent requests'; ?></h2><p><?php echo $is_employee ? 'Review details and update each request as it moves forward.' : 'Track the latest status from the Sun Son team.'; ?></p></div></div>
          <?php if (!$requests): ?>
            <div class="empty-state"><strong><?php echo $is_employee ? 'No customer requests yet' : 'No requests yet'; ?></strong><p><?php echo $is_employee ? 'New quote and service requests will appear here.' : 'When you request a quote or service, its progress will show here.'; ?></p>
              <?php if (!$is_employee): ?><a class="button button-lime" href="<?php echo base_url(); ?>#contact">Tell us what you need <span aria-hidden="true">↗</span></a><?php endif; ?>
            </div>
          <?php else: ?>
            <div class="request-list">
              <?php foreach ($requests as $request): ?>
                <?php $status_class = 'status-' . strtolower($request['status']); ?>
                <article class="request-row <?php echo $is_employee ? 'employee-row' : ''; ?>">
                  <div class="request-primary"><strong><?php echo $is_employee ? '#' . (int) $request['id'] . ' · ' . html_escape($request['name']) : html_escape($request['service']); ?></strong><small><?php echo html_escape(date('M j, Y', strtotime($request['created_at']))); ?></small></div>
                  <?php if ($is_employee): ?>
                    <div class="request-service"><?php echo html_escape($request['service']); ?></div>
                    <div class="request-contact"><a href="mailto:<?php echo html_escape($request['email']); ?>"><?php echo html_escape($request['email']); ?></a><a href="tel:<?php echo html_escape($request['phone']); ?>"><?php echo html_escape($request['phone']); ?></a></div>
                  <?php else: ?>
                    <div class="request-service">Request update</div>
                  <?php endif; ?>
                  <span class="status-pill <?php echo html_escape($status_class); ?>"><?php echo html_escape($request['status']); ?></span>
                  <?php if ($is_employee): ?>
                    <?php if (!empty($request['message'])): ?><p class="request-details"><?php echo html_escape($request['message']); ?></p><?php endif; ?>
                    <?php echo form_open('auth/update_quote_status', array('class' => 'status-form')); ?>
                      <input type="hidden" name="request_id" value="<?php echo (int) $request['id']; ?>">
                      <label class="sr-only" for="status-<?php echo (int) $request['id']; ?>">Update status for request <?php echo (int) $request['id']; ?></label>
                      <select id="status-<?php echo (int) $request['id']; ?>" name="status">
                        <?php foreach (array('New', 'Contacted', 'Scheduled', 'Completed') as $option): ?><option value="<?php echo $option; ?>" <?php echo $request['status'] === $option ? 'selected' : ''; ?>><?php echo $option; ?></option><?php endforeach; ?>
                      </select><button type="submit">Update</button>
                    </form>
                  <?php else: ?>
                    <?php if (!empty($request['message'])): ?><p class="request-details"><?php echo html_escape($request['message']); ?></p><?php endif; ?>
                    <span class="request-meta">Request #<?php echo (int) $request['id']; ?></span>
                  <?php endif; ?>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

        <section class="dashboard-panel" aria-labelledby="orders-title"><div class="panel-heading"><div><h2 id="orders-title"><?php echo $is_employee ? 'Shop order queue' : 'Your solar orders'; ?></h2><p><?php echo $is_employee ? 'Confirm orders and keep customers updated.' : 'Follow your plan and service order requests.'; ?></p></div><?php if (!$is_employee): ?><a class="button button-lime" href="<?php echo site_url('shop'); ?>">Browse solar plans</a><?php endif; ?></div>
          <?php if (!$orders): ?><div class="empty-state"><strong><?php echo $is_employee ? 'No shop orders yet' : 'No solar orders yet'; ?></strong><p><?php echo $is_employee ? 'Customer shop requests will appear here.' : 'Browse solar packages and maintenance plans when you are ready.'; ?></p><?php if (!$is_employee): ?><a class="button button-lime" href="<?php echo site_url('shop'); ?>">Visit the solar shop</a><?php endif; ?></div><?php else: ?>
            <?php foreach ($orders as $order): ?><article class="order-row"><div><span class="order-name">Order #<?php echo (int)$order['id']; ?><?php if ($is_employee): ?> · <?php echo html_escape(trim($order['first_name'].' '.$order['last_name'])); ?><?php endif; ?></span><span class="order-sub"><?php echo html_escape(date('M j, Y',strtotime($order['created_at']))); ?> · ₱<?php echo number_format($order['total'],2); ?><?php if ($is_employee && !empty($order['email'])): ?> · <?php echo html_escape($order['email']); ?><?php endif; ?></span></div><span class="status-pill status-<?php echo strtolower(html_escape($order['status'])); ?>"><?php echo html_escape($order['status']); ?></span><?php if ($is_employee): ?><?php echo form_open('shop/update_order_status',array('class'=>'order-status-form')); ?><input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>"><label class="sr-only" for="order-status-<?php echo (int)$order['id']; ?>">Update order status <?php echo (int)$order['id']; ?></label><select id="order-status-<?php echo (int)$order['id']; ?>" name="status"><?php foreach (array('New','Confirmed','Scheduled','Completed','Cancelled') as $option): ?><option value="<?php echo $option; ?>" <?php echo $order['status']===$option?'selected':''; ?>><?php echo $option; ?></option><?php endforeach; ?></select><button type="submit">Update</button></form><?php else: ?><a href="<?php echo site_url('shop/order/'.(int)$order['id']); ?>">Order details →</a><?php endif; ?></article><?php endforeach; ?>
          <?php endif; ?></section>

        <?php if ($is_employee): ?>
          <aside class="employee-guidance"><div><strong>Employee access</strong><p>Use the request queue to follow up on consultation, installation, and maintenance leads.</p></div><div><strong>Account provisioning</strong><p>Employee accounts are created by HR or an administrator. Contact them if a teammate needs access.</p></div></aside>
        <?php else: ?>
          <section class="dashboard-panel"><div class="panel-heading"><div><h2>A little more control, every day</h2><p>We’ll help you make the right solar choices for your home.</p></div></div><div class="dashboard-links"><a href="<?php echo base_url(); ?>#about">Explore solar benefits ↗</a><a href="<?php echo base_url(); ?>#services">View consultation, installation, and maintenance ↗</a><a href="<?php echo base_url(); ?>#faq">Read common questions ↗</a></div></section>
        <?php endif; ?>

        <p class="dashboard-footer">Signed in as <?php echo html_escape($account['username']); ?> · <a href="mailto:hello@sunsonsolar.com">Need help? Contact Sun Son</a></p>
      </section>
    </div>
  </main>
</body>
</html>
