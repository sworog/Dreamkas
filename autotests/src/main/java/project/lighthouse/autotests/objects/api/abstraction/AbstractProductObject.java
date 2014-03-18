package project.lighthouse.autotests.objects.api.abstraction;

import org.json.JSONObject;

public class AbstractProductObject {

    private JSONObject jsonObject;

    public AbstractProductObject(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public JSONObject getJsonObject() {
        return jsonObject;
    }
}
