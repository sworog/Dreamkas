package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.commercialManager.SupplierApiSteps;

import java.io.IOException;

public class EndSupplierApiSteps {

    @Steps
    SupplierApiSteps supplierApiSteps;

    @Given("there is the supplier with name '$name'")
    public void givenThereIsTheSupplierWithName(String name) throws IOException, JSONException {
        supplierApiSteps.createSupplier(name);
    }
}
