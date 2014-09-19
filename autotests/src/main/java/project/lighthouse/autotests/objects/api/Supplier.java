package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

/**
 * The class representing the supplier api object
 */
public class Supplier extends AbstractObject {

    public Supplier(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Supplier(String name,
                    String address,
                    String phone,
                    String email,
                    String contactPerson) throws JSONException {
        this(new JSONObject()
                        .put("name", name)
                        .put("address", address)
                        .put("phone", phone)
                        .put("email", email)
                        .put("contactPerson", contactPerson)
        );
    }

    @Override
    public String getApiUrl() {
        return "/suppliers";
    }
}
