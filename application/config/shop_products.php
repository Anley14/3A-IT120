<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Illustrative Sun Son Solar catalog values. Staff confirms final scope and pricing.
$config['shop_products'] = array(
    'home-assessment' => array(
        'id'=>'home-assessment', 'name'=>'Home Solar Assessment', 'category'=>'Consultation',
        'price'=>2500.00, 'price_note'=>'one-time | credited toward installation',
        'badge'=>'Start here', 'icon'=>'01',
        'description'=>'A practical review of your energy use, roof, and solar options with a clear system recommendation.',
        'features'=>array('Roof and shade review','Usage and savings estimate','Personalized system proposal')
    ),
    'sunrise-home' => array(
        'id'=>'sunrise-home', 'name'=>'Sunrise Home Package', 'category'=>'Solar package',
        'price'=>189000.00, 'price_note'=>'starting estimate | final design varies',
        'badge'=>'Popular', 'icon'=>'SUN',
        'description'=>'A right-sized grid-tied solar package designed for everyday household energy needs.',
        'features'=>array('3 kW panel system estimate','Grid-tied inverter','Professional site assessment')
    ),
    'bright-home-plus' => array(
        'id'=>'bright-home-plus', 'name'=>'Bright Home Plus', 'category'=>'Solar package',
        'price'=>329000.00, 'price_note'=>'starting estimate | final design varies',
        'badge'=>'More independence', 'icon'=>'PLUS',
        'description'=>'A larger solar package with a home battery consultation for added energy flexibility.',
        'features'=>array('5 kW panel system estimate','Battery-ready inverter','Battery sizing consultation')
    ),
    'solar-care' => array(
        'id'=>'solar-care', 'name'=>'Solar Care Annual', 'category'=>'Maintenance',
        'price'=>6800.00, 'price_note'=>'per year | limited-time launch offer',
        'badge'=>'Launch offer', 'icon'=>'CARE',
        'description'=>'An annual check-in to help keep your solar system clean, monitored, and performing well.',
        'features'=>array('One system health inspection','Performance report','Priority support booking')
    ),
    'solar-care-plus' => array(
        'id'=>'solar-care-plus', 'name'=>'Solar Care Plus', 'category'=>'Maintenance',
        'price'=>12800.00, 'price_note'=>'per year | limited-time launch offer',
        'badge'=>'Best coverage', 'icon'=>'PLUS',
        'description'=>'More hands-on yearly care with two scheduled visits and a closer look at system performance.',
        'features'=>array('Two scheduled system visits','Panel and inverter inspection','Priority service coordination')
    ),
    'performance-review' => array(
        'id'=>'performance-review', 'name'=>'Performance Review', 'category'=>'Maintenance',
        'price'=>1800.00, 'price_note'=>'one-time service',
        'badge'=>'Quick check', 'icon'=>'CHECK',
        'description'=>'A focused review when your energy production looks different or you want a system check.',
        'features'=>array('Remote performance review','Issue summary','Recommended next steps')
    )
);
