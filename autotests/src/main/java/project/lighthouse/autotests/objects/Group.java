package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Group {

    JSONObject jsonObject;

    public static final String jsonPattern = "{\"group\":{\"name\":\"%s\",\"klass\":\"%s\"}}";

    public Group(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }
}
