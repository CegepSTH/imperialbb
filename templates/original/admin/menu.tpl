<table width="100%" class="maintable">
    <tr>
        <td class="cell1" align="center">
            <strong style="font-size:11px;">
              ImperialBB Version {C.version}<br />
              <a href="javascript:void(0);" onclick="return ibb_expandall();" title="{L.expandall}">{L.expandall}</a> - <a href="javascript:void(0);" onclick="return ibb_collapseall();" title="{L.collapseall}">{L.collapseall}</a>
              </strong>
        </td>
    </tr>
</table><br />
<div style="margin-bottom:8px;">
    <table width="100%" class="maintable">
        <thead>
            <tr>
                <th height="25" align="left" width="100%">
                    <div style="float:right; padding-right:4px; padding-top:3px;"><a name="{L.General_Links}" href="javascript:void(0);" onclick="return ibb_toggle('{L.General_Links}');"><img src="{T.TEMPLATE_PATH}/images/plus.gif" id ="img_{L.General_Links}" border="0" alt="{L.collapse}" title="{L.collapse}" /></a></div><span style="padding-left:4px;"><a name="{L.General_Links}" href="javascript:void(0);" onclick="return ibb_toggle('{L.General_Links}');">{L.General_Links}</a></span>
                </th>
            </tr>
        </thead>
        <tbody id="menu_{L.General_Links}">
            <tr>
                <td class="cell2">
                    <a href="main.php" target="main">{L.Admin_Home}</a><br>
                    <a href="../index.php" target="_parent">{L.Forum_Home}</a><br>
                    <a href="../index.php" target="main">{L.Forum_Preview}</a><br>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- BEGIN link_block -->
<div style="margin-bottom:8px;">
    <table width="100%" class="maintable">
        <thead>
            <tr>
                <th height="25" align="left" width="100%">
                    <div style="float:right; padding-right:4px; padding-top:3px;"><a name="{SECTION}" href="javascript:void(0);" onclick="return ibb_toggle('{SECTION}');"><img src="{T.TEMPLATE_PATH}/images/plus.gif" id ="img_{SECTION}" border="0" alt="{L.collapse}" title="{L.collapse}" /></a></div><span style="padding-left:4px;"><a name="{SECTION}" href="javascript:void(0);" onclick="return ibb_toggle('{SECTION}');">{SECTION}</a></span>
                </th>
            </tr>
        </thead>
        <tbody id="menu_{SECTION}">
            <tr>
                <td class="cell2">
                    <!-- BEGIN link -->
                        <a href="{LINK}" target="main">{NAME}</a><br>
                    <!-- END link -->
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- END link_block -->
