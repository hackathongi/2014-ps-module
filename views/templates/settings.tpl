{$message}

<fieldset>
    <legend>Settings</legend>
    <form method="post">
        <p>
            <label for="MOD_ESHOPINION_API_USERNAME">API Username:</label>
            <input id="MOD_ESHOPINION_API_USERNAME" name="MOD_ESHOPINION_API_USERNAME" type="text" value="{$MOD_ESHOPINION_API_USERNAME}" />
        </p>
        <p>
            <label for="MOD_ESHOPINION_API_KEY">API Key:</label>
            <input id="MOD_ESHOPINION_API_KEY" name="MOD_ESHOPINION_API_KEY" type="text" value="{$MOD_ESHOPINION_API_KEY}" />
        </p>
        <p>
            <label>&nbsp;</label>
            <input id="submit_{$module_name}" name="submit_{$module_name}" type="submit" value="Save" class="button" />
        </p>
    </form>
</fieldset>
