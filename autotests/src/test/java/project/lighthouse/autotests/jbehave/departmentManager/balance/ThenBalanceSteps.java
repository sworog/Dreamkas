package project.lighthouse.autotests.jbehave.departmentManager.balance;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.BalanceSteps;

public class ThenBalanceSteps {

    @Steps
    BalanceSteps balanceSteps;

    private ExamplesTable itemsExampleTable;

    @Then("the user checks the product balance list contains entry $examplesTable")
    public void thenTheUserChecksTheProductBalanceList(ExamplesTable examplesTable) {
        balanceSteps.compareWithExampleTable(examplesTable);
    }

    @Then("the user checks product balance tab is not visible")
    public void thenTheUserChecksTheProductBalanceTabIsNotVisible() {
        balanceSteps.balanceTabIsNotVisible();
    }

    @Then("the user checks the balance list item by name '$name' has items not visible $examplesTable")
    public void thenTheUserChecksTheBalanceListItemsHasItemsNotVisible(String name, ExamplesTable examplesTable) {
        balanceSteps.checkItemsAreNotVisible(name, examplesTable);
        itemsExampleTable = examplesTable;
    }

    @Then("the user checks the balance list item by name '$name' has items become visible while hovering")
    public void thenTheUserChecksTheBalanceListItemsHasItemsVisible(String name) {
        balanceSteps.checkItemsAreVisible(name, itemsExampleTable);
    }

    @Then("the user checks the product with name '$name' has inventory equals to '$expectedValue'")
    public void thenTheUserChecksTheProductWithNameHasInventory(String name, String expectedValue) {
        balanceSteps.balanceObjectItemHasInventoryByLocator(name, expectedValue);
    }
}
