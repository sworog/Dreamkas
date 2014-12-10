package ru.dreamkas.api.objects.token;

import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;

import static org.junit.Assert.*;
import static org.hamcrest.Matchers.is;

public class OauthAuthorizeDataTest {

    @Test
    public void testAccessTokenGetter() throws JSONException {
        assertThat(
                new OauthAuthorizeData(new JSONObject("{access_token: token}")).getAccessToken(),
                is("token"));
    }
}
