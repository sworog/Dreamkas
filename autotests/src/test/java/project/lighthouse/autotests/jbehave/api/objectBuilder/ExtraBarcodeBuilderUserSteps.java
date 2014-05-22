package project.lighthouse.autotests.jbehave.api.objectBuilder;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;
import project.lighthouse.autotests.steps.api.objectBuilder.ExtraBarcodeBuilderSteps;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;

public class ExtraBarcodeBuilderUserSteps {

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    ExtraBarcodeBuilderSteps extraBarcodeBuilderSteps;

    @Given("the user adds the extra barcode with barcode '$barcode', quantity '$quantity' and price '$price' to the barcode array")
    public void givenTheUserAddsTheExtraBarcodeToTheBarcodeArray(String barcode, String quantity, String price) throws JSONException {
        extraBarcodeBuilderSteps.addExtraBarcodeToBarcodeArray(barcode, quantity, price);
    }

    @Given("the user adds the stored barcodes to the product with name '$name'")
    public void givenTheUserAddsTheStoredBarcodesToTheProductWithName(String name) throws IOException, JSONException {
        Product product = StaticData.products.get(name);
        productApiSteps.addProductExtraBarcodes(product, Storage.getCustomVariableStorage().getExtraBarcodes());
    }
}
