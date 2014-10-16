package ru.dreamkas.jbehave.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.steps.supplier.SupplierSteps;

public class ThenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Then("the user asserts the supplier list contain supplier with values $examplesTable")
    @Alias("пользователь проверяет, что список поставщиков содержит поставщика с данными $examplesTable")
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

    @Then("the user asserts that supplier list contain supplier with stored name")
    public void thenTheUserAssertsThatSupplierListContainSupplierWithName() {
        supplierSteps.supplierCollectionContainSupplierWithName();
    }

    @Then("the user asserts that supplier list contain supplier with name '$name'")
    public void thenTheUserAssertsThatSupplierListContainSupplierWithName(String name) {
        supplierSteps.supplierCollectionContainSupplierWithName(name);
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

    @Then("the user checks the create new supplier modal window '$elementName' field has error message with text '$message'")
    public void thenTheUserChecksTheCreateNewSupplierModalWindowFieldHasErrorMessage(String elementName, String message) {
        supplierSteps.supplierCreateModalPageCheckErrorMessage(elementName, message);
    }

    @Then("the user checks the edit supplier modal window '$elementName' field has error message with text '$message'")
    public void thenTheUserChecksTheEditSupplierModalWindowFieldHasErrorMessage(String elementName, String message) {
        supplierSteps.supplierEditModalPageCheckErrorMessage(elementName, message);
    }
}
