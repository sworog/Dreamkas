package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class OauthAuthorizeData {

    JSONObject jsonObject;

    public OauthAuthorizeData(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getAccessToken() throws JSONException {
        return jsonObject.getString("access_token");
    }
}
