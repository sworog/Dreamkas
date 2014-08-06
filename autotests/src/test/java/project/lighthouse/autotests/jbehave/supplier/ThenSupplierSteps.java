package project.lighthouse.autotests.jbehave.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.supplier.SupplierSteps;

public class ThenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Then("the user asserts the supplier list contain supplier with values $examplesTable")
    public void thenTheUserAssertsTheSupplierListContainSupplerWithValues(ExamplesTable examplesTable) {
        supplierSteps.supplierCollectionCompareWithExampleTable(examplesTable);
    }

    @Then("the user asserts that supplier contain stored values in edit supplier modal window")
    public void thenTheUserAssertsThatSupplierContainStoredValuesInEditSupplierModalWindow() {
        supplierSteps.supplierEditModalPageCheckValues();
    }

    @Then("the user asserts that supplier list not contain supplier with name '$name'")
    public void thenTheUserAssertsThatSupplierListNotContainSupplierWithName(String name) {
        supplierSteps.supplierCollectionNoContainSupplierWithName(name);
    }

    @Then("the user asserts the create new supplier modal window title is '$title'")
    public void thenTheUserAssertsTheCreateNewSupplierModalWindowTitle(String title) {
        supplierSteps.assertSupplierCreateSupplierModalWindowTitle(title);
    }

    @Then("the user asserts the edit supplier modal window title is '$title'")
    public void thenTheUserAssertsTheEditSupplierModalWindowTitle(String title) {
        supplierSteps.assertSupplierEditSupplierModalWindowTitle(title);
    }

    @Then("the user asserts suppliers list page title is '$title'")
    public void thenTheUserAssertsSupplierListPageTitle(String title) {
        supplierSteps.assertSupplierListPageTitle(title);
    }
}
