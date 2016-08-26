{{--<div class="well">--}}
   {{----}}
{{--</div>--}}
<div class="panel panel-default">
    <div class="panel-heading">Navigation</div>
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
            @each(CMFTemplate('shared.sidenav_item'), $cmf_navigation_sidebar, 'sidebar_item')
        </ul>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Settings</div>
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
            @each(CMFTemplate('shared.sidenav_item'), $cmf_settings_sidebar, 'sidebar_item')
        </ul>
    </div>
</div>

