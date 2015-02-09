<!-- BLOCK category -->
<div class="panel config-panel">
    <form method="post" action="" style="margin: 0px; padding: 0px;">
		<div class="panel-header">
			{CATEGORY_TITLE}
		</div>
		<div class="panel-body">
			{CATEGORY_CONFIG_OPTIONS}
            <!-- BLOCK config_option -->
            <div class="config-option clearfix">
                <div class="config-name">{CONFIG_TITLE}</div>
                <div class="config-value">{CONFIG_CONTENT}</div>
            </div>
            <!-- END BLOCK config_option -->
		</div>
		<div class="panel-footer">
			<input type="submit" name="Submit" value="{L.Submit}" />
			<input type="reset" value="{L.Reset}" />
		</div>
    </form>
</div>
<!-- END BLOCK category -->
