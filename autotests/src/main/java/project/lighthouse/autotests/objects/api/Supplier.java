package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * The class representing the supplier api object
 */
public class Supplier extends AbstractObject {

    public Supplier(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Supplier(String name) throws JSONException {
        this(new JSONObject()
                .put("name", name)
        );
    }

    @Override
    public String getApiUrl() {
        return "/suppliers";
    }
}
