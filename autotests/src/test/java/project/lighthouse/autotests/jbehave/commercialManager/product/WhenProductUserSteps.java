package project.lighthouse.autotests.jbehave.commercialManager.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class WhenProductUserSteps {

    @Steps
    ProductSteps productSteps;

    @When("the user clicks the product local navigation writeoffs link")
    public void whenTheUSerClicksTheProductLocalNavigationWriteOffsLink() {
        productSteps.productWriteOffsLinkClick();
    }

    @When("the user clicks the product local navigation returns link")
    public void whenTheUSerClicksTheProductLocalNavigationReturnsLink() {
        productSteps.productReturnsLinkClick();
    }

    @When("the user clicks on the product writeOff with '$number' number")
    public void whenTheUserClicksOnTheProductWriteOff(String number) {
        productSteps.productWriteOffListObjectClick(number);
    }

    @When("the user clicks on product with name '$name'")
    @Alias("the user clicks on product with <productName>")
    public void whenTheUserClicksOnProductWithName(String productName) {
        productSteps.productObjectClickByLocator(productName);
    }

    @When("the user clicks the product local navigation barcodes link")
    public void whenTheUserClicksTheProductLocalNavigationBarCodesLink() {
        productSteps.barCodesLinkClick();
    }

    @When("the user inputs values on product extra barcode page $examplesTable")
    public void whenTheUserInputsOnProductExtraBarcodePage(ExamplesTable examplesTable) {
        productSteps.barcodePageInput(examplesTable);
    }

    @When("the user inputs value in element with name '$elementName' on product extra barcode page")
    public void whenTheUserInputsOnProductExtraBarcodePage(String value, String elementName) {
        productSteps.barcodePageInput(elementName, value);
    }

    @When("the user clicks on add extra barcode button")
    public void whenTheUserClicksOnAddExtraBarcodeButton() {
        productSteps.addBarcodeButtonClick();
    }

    @When("the user clicks on save extra barcode button")
    public void whenTheUserClicksOnSaveExtraBarcodeButton() {
        productSteps.saveBarcodeButtonClick();
    }

    @When("the user types '$value' in barcode element with barcode '$barcode'")
    public void whenTheUserTypesValueInBarcodeElementWithBarcode(String value, String barcode) {
        productSteps.barcodeObjectBarcodeType(barcode, value);
    }

    @When("the user types '$value' in quantity element with barcode '$barcode'")
    public void whenTheUserTypesValueInQuantityElementWithBarcode(String value, String barcode) {
        productSteps.quantityObjectBarcodeType(barcode, value);
    }

    @When("the user types '$value' in price element with barcode '$barcode'")
    public void whenTheUserTypesValueInPriceElementWithBarcode(String value, String barcode) {
        productSteps.priceObjectBarcodeType(barcode, value);
    }

    @When("the user deletes barcode with barcode '$barcode'")
    public void whenTheUserDeletesBarcodeWithBarcode(String barcode) {
        productSteps.priceObjectDelete(barcode);
    }

    @When("the user clicks on cancel save extra barcode button")
    public void whenTheUserClicksOnCancelSaveExtraBarcodeButton() {
        productSteps.cancelBarcodeSaveLinkClick();
    }
}
