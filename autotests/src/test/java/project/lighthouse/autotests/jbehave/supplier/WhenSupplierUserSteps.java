package project.lighthouse.autotests.jbehave.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.supplier.SupplierSteps;

public class WhenSupplierUserSteps {

    @Steps
    SupplierSteps supplierSteps;

    @When("the user clicks on the add new supplier create button")
    public void whenTheUserClicksOnTheAddNewSupplierCreateButton() {
        supplierSteps.addNewSupplierButtonClick();
    }

    @When("the user inputs values on the create new supplier modal window $examplesTable")
    public void whenTheUserInputsValuesOnTheCreateNewSupplierModalWindow(ExamplesTable examplesTable) {
        supplierSteps.supplierCreateModalPageInput(examplesTable);
    }

    @When("the user inputs values on the edit supplier modal window $examplesTable")
    public void whenTheUserInputsValuesOnTheEditSupplierModalWindow(ExamplesTable examplesTable) {
        supplierSteps.supplierEditModalPageInput(examplesTable);
    }

    @When("the user clicks on add button on the create new supplier modal window")
    public void whenTheUserClicksOnAddButtonOnTheCreateNewSupplierModalWindow() {
        supplierSteps.supplierCreateModalPageConfirmButtonClick();
    }

    @When("the user clicks on save button on the edit supplier modal window")
    public void whenTheUserClicksOnSaveButtonOnTheEditSupplierModalWindow() {
        supplierSteps.supplierEditModalPageConfirmButtonClick();
    }

    @When("the user clicks on close icon on the create new supplier modal window")
    public void whenTheUserClicksOnCloseIconOnTheCreateNewSupplierModalWindow() {
        supplierSteps.supplierCreateModalPageCloseIconClick();
    }

    @When("the user clicks on close icon on the edit supplier modal window")
    public void whenTheUserClicksOnCloseIconOnTheEditSupplierModalWindow() {
        supplierSteps.supplierEditModalPageCloseIconClick();
    }

    @When("the user clicks on the supplier with name '$name'")
    public void whenTheUserClicksOnTheSupplierWithName(String name) {
        supplierSteps.clickOnTheSupplierWithName(name);
    }
}
