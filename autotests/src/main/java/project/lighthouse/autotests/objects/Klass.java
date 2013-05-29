package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Klass {

    JSONObject jsonObject;

    public static final String jsonPattern = "{\"klass\":{\"name\":\"%s\"}}";

    public Klass(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }
}
