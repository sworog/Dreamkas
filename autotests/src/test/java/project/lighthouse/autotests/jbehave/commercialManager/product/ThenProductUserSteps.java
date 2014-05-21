package project.lighthouse.autotests.jbehave.commercialManager.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class ThenProductUserSteps {

    @Steps
    ProductSteps productSteps;

    @Then("the user checks the product writeOff list contains entry $examplesTable")
    public void thenTheUserChecksTheProductWriteOffListContainsEntry(ExamplesTable examplesTable) {
        productSteps.checkProductWriteOffListObject(examplesTable);
    }

    @Then("the user checks the product local navigation writeoffs link is not present")
    public void thenTheUserChecksTheProductLocalNaviWriteOffsLinkIsNotPresent() {
        productSteps.productWriteOffLinkIsNotPresent();
    }

    @Then("the user sees no edit product button")
    public void thenTheUserSeesNoEditProductButton() {
        productSteps.editProductButtonIsNotPresent();
    }

    @Then("the user sees no create new product button")
    public void thenTheUserSeesNoCreateNewProductButton() {
        productSteps.newProductCreateButtonIsNotPresent();
    }

    @Then("the user checks the products list contain product with name '$name'")
    public void thenTheUserChecksTheProductsListContainProductWithName(String name) {
        productSteps.productObjectCollectionContainObjectWithLocator(name);
    }

    @Then("the user checks product list contains values $examplesTable")
    public void thenTheUserChecksProductListContainValues(ExamplesTable examplesTable) {
        productSteps.productListObjectCollectionCompareWithExamplesTable(examplesTable);
    }

    @Then("the user checks the product with name '$name' has purchasePrice equals to '$expectedValue'")
    public void thenTheUserChecksTheProductWithNameHasPurchasePriceEqualsToExpectedValue(String name,
                                                                                         String expectedValue) {
        productSteps.assertProductListObjectPurchasePrice(name, expectedValue);
    }

    @Then("the user checks the product with name '$name' has sku equals to '$expectedValue'")
    public void thenTheUserChecksTheProductWithNameHasSkuEqualsToExpectedValue(String name,
                                                                               String expectedValue) {
        productSteps.assertProductListObjectSku(name, expectedValue);
    }

    @Then("the user checks the product sku field is not visible")
    public void thenTheUserChecksTheProductSkuFieldIsNotVisible() {
        productSteps.assertSkuFieldIsNotVisible();
    }

    @Then("the user checks the element field '$elementName' has error message '$errorMessage'")
    @Alias("the user checks the element field '$elementName' has errorMessage")
    public void thenTheUserChecksTheElementFieldHasErrorMessage(String elementName, String errorMessage) {
        productSteps.assertFieldErrorMessage(elementName, errorMessage);
    }

    @Then("the user checks the extra barcodes list contains exact entries $examplesTable")
    public void thenTheUserChecksTheExtraBarcodesListContainsExactValues(ExamplesTable examplesTable) {
        productSteps.barcodeCollectionExactCompareWithExamplesTable(examplesTable);
    }
}
