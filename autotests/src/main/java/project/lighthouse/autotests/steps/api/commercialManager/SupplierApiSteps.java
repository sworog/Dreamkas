package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.objects.api.Supplier;

import java.io.IOException;

public class SupplierApiSteps extends OwnerApi {

    @Step
    public Supplier createSupplier() throws IOException, JSONException {
        String uuid = new UUIDGenerator().generate();
        return apiConnect.createSupplier(uuid);
    }

    @Step
    public Supplier createSupplier(String name) throws IOException, JSONException {
        return apiConnect.createSupplier(name);
    }

    @Step
    public void navigateToSupplierPage(String supplierName) throws JSONException {
        String supplierPageUrl = apiConnect.getSupplierPageUrl(supplierName);
        getDriver().navigate().to(supplierPageUrl);
    }

    @Step
    public void navigateToSupplierPageWithRandomName() throws IOException, JSONException {
        String uuid = new UUIDGenerator().generate();
        createSupplier(uuid);
        navigateToSupplierPage(uuid);
    }
}
