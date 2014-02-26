package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Supplier;

import java.io.IOException;

public class SupplierApiSteps extends CommercialManagerApi {

    public SupplierApiSteps() throws IOException, JSONException {
    }

    @Step
    public Supplier createSupplier(String name) throws IOException, JSONException {
        return apiConnect.createSupplier(name);
    }
}
