<form id="cpmlogin" method=post action="index.php?a=login" onsubmit="" name=login>
    <table cellpadding="0" cellspacing="0" style="width:540;">
        <tr>
            <td class="box_title">User Login</td>
        </tr>
        <tr>
            <td class="box_content" style="padding-left:20px;">
                Enter the username and password for site administrator.
            </td>
        </tr>
        <tr>
            <td class="box_content">
                USERNAME: &nbsp;
                <input type="text" name="user" value="{{ username }}"/>
            </td>
        </tr>
        <tr>
            <td class="box_content">
                PASSWORD: &nbsp;
                <input type="password" name="pass">
            </td>
        </tr>
        <tr>
            <td class="box_content">
                <button type="submit" form="cpmlogin">Login</button>
                <input type="hidden" value="login" name="cpm_login" />
                <input type="hidden" value="{{ fail }}" name="cpm_fail" />
                <input type="hidden" value="{{ redirect }}" name="cpm_redir" />
            </td>
        </tr>
        <tr>
            <td>
                <a href="index.html?a=fpf">Forgot Pasword?</a>
            </td>
        </tr>
    </table>
</form>