package project.lighthouse.autotests.jbehave.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.product.ProductSteps;

public class WhenProductSteps {

    @Steps
    ProductSteps productSteps;

    @When("the user clicks on create product button on group page")
    public void whenTheUserClicksOnCreateProductButtonOnGroupPage() {
        productSteps.createNewProductButtonClick();
    }

    @When("the user inputs values in create new product modal window $examplesTable")
    public void whenTheUserInputsValuesInCreateNewProductModalWindow(ExamplesTable examplesTable) {
        productSteps.createNewProductModalWindowInput(examplesTable);
    }

    @When("the user confirms OK in create new product modal window")
    public void whenTheUserConfirmsOKInCreateNewProductModalWindow() {
        productSteps.createNewProductModalWindowConfirmOkClick();
    }

    @When("the user clicks on the product with name '$name'")
    public void whenTheUserClicksOnTheProductWithName(String name) {
        productSteps.productCollectionProductWithNameClick(name);
    }

    @When("the user clicks on close icon in create new product modal window")
    public void whenTheUserClicksOnCloseIconInCreateNewProductModalWindow() {
        productSteps.createNewProductModalWindowCloseIconClick();
    }

    @When("the user clicks on delete product button in edit product modal window")
    public void whenTheUserClicksOnDeleteProductButtonClickInEditProductModalWindow() {
        productSteps.deleteButtonClick();
    }

    @When("the user clicks on delete product confirm button in edit product modal window")
    public void whenTheUserClicksOnDeleteProductConfirmButtonInEditProductModalWindow() {
        productSteps.confirmDeleteButtonClick();
    }
}
