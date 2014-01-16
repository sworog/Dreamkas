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

    public String get() throws JSONException, IOException {
        if (!StaticData.userTokens.containsKey(userName)) {
            String url = String.format("%s/oauth/v2/token", UrlHelper.getApiUrl());
            String parameters = String.format("?grant_type=password&username=%s&password=%s&client_id=%s&client_secret=%s",
                    userName, password, StaticData.client_id, StaticData.client_secret);
            String response = new HttpExecutor(null).executeSimpleGetRequest(url + parameters, false);
            String accessToken = new OauthAuthorizeData(new JSONObject(response)).getAccessToken();
            StaticData.userTokens.put(userName, accessToken);
        }
        return StaticData.userTokens.get(userName);
    }
}
