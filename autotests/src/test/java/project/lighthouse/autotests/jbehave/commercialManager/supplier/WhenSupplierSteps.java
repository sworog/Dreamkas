package project.lighthouse.autotests.jbehave.commercialManager.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.supplier.SupplierSteps;

public class WhenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @When("the user inputs values on supplier page $examplesTable")
    public void whenTheUserInputsValuesOnSupplierPage(ExamplesTable examplesTable) {
        supplierSteps.input(examplesTable);
    }

    @When("the user inputs value in supplierName field on supplier page")
    public void whenTheUserInputsValueOnSupplierPage(String value) {
        supplierSteps.input("supplierName", value);
    }

    @When("the user clicks on the supplier create button")
    public void whenTheUserClicksOnTheSupplierCreateButton() {
        supplierSteps.createButtonClick();
    }

    @When("the user clicks on the supplier cancel button")
    public void whenTheUserClicksOnTheSupplierCancelButton() {
        supplierSteps.cancelButtonClick();
    }

    @When("the user generate test data with char number '$number' to the supplier field name '$elementName'")
    public void whenTheUserGeneratesTestDataWithNumberToTheField(int number, String elementName) {
        supplierSteps.generateString(elementName, number);
    }

    @When("the user clicks on supplier list table element with name '$supplierName'")
    public void whenTheUserClicksOnSupplierTableElementWithName(String supplierName) {
        supplierSteps.supplierCollectionObjectClickByLocator(supplierName);
    }
}
