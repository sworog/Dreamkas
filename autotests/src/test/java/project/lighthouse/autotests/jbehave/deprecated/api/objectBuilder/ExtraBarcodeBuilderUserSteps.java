package project.lighthouse.autotests.jbehave.deprecated.api.objectBuilder;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.deprecated.api.objectBuilder.ExtraBarcodeBuilderSteps;

public class ExtraBarcodeBuilderUserSteps {

    @Steps
    ExtraBarcodeBuilderSteps extraBarcodeBuilderSteps;

    @Given("the user adds the extra barcode with barcode '$barcode', quantity '$quantity' and price '$price' to the barcode array")
    public void givenTheUserAddsTheExtraBarcodeToTheBarcodeArray(String barcode, String quantity, String price) throws JSONException {
        extraBarcodeBuilderSteps.addExtraBarcodeToBarcodeArray(barcode, quantity, price);
    }
}
