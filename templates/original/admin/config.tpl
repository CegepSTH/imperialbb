<!-- BLOCK category -->
<div style="margin-bottom: 8px;">
    <form method="post" action="" style="margin: 0px; padding: 0px;">
        <table width="100%" class="maintable">
            <tr>
                <th colspan="2" height="25">{CATEGORY_TITLE}</th>
            </tr>
			{CATEGORY_CONFIG_OPTIONS}
            <!-- BLOCK config_option -->
            <tr>
                <td width="30%" class="cell2">{CONFIG_TITLE}</td>
                <td width="70%" class="cell1">{CONFIG_CONTENT}</td>
            </tr>
            <!-- END BLOCK config_option -->
            <tr>
                <th colspan="2" height="25">
                    <input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" />
                </th>
            </tr>
        </table>
    </form>
</div>
<!-- END BLOCK category -->
