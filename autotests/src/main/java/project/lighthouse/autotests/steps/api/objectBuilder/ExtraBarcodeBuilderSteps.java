package project.lighthouse.autotests.steps.api.objectBuilder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.product.ExtraBarcode;
import project.lighthouse.autotests.storage.Storage;

public class ExtraBarcodeBuilderSteps extends ScenarioSteps {

    @Step
    public void addExtraBarcodeToBarcodeArray(String barcode, String quantity, String price) throws JSONException {
        Storage.getCustomVariableStorage().getExtraBarcodes().add(new ExtraBarcode(barcode, quantity, price));
    }
}
