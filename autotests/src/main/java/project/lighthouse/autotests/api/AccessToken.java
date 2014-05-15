package project.lighthouse.autotests.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.OauthAuthorizeData;

import java.io.IOException;

public class AccessToken {

    private final String userName;
    private final String password;

    public AccessToken(String userName, String password) {
        this.userName = userName;
        this.password = password;
    }

    public String get() {
        if (!StaticData.userTokens.containsKey(userName)) {
            String accessToken;
            try {
                String url = String.format("%s/oauth/v2/token", UrlHelper.getApiUrl());
                JSONObject jsonObject = new JSONObject()
                        .put("grant_type", "password")
                        .put("username", userName)
                        .put("password", password)
                        .put("client_id", StaticData.client_id)
                        .put("client_secret", StaticData.client_secret);
                String response = HttpExecutor.getSimpleHttpRequestable().executeSimplePostRequest(url, jsonObject.toString());
                accessToken = new OauthAuthorizeData(new JSONObject(response)).getAccessToken();
            } catch (JSONException | IOException e) {
                throw new AssertionError(e.getMessage());
            }
            StaticData.userTokens.put(userName, accessToken);
            return accessToken;
        } else {
            return StaticData.userTokens.get(userName);
        }
    }
}
