package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class AbstractClassifierNode extends AbstractObject {

    public AbstractClassifierNode(JSONObject jsonObject1) {
        super(jsonObject1);
    }

    public AbstractClassifierNode(String name) throws JSONException {
        super();
        jsonObject.put("name", name)
                .put("rounding", "nearest1");
    }

    @Override
    public String getApiUrl() {
        return null;
    }
}
