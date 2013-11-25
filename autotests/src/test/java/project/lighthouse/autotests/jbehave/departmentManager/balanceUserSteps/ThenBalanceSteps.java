package project.lighthouse.autotests.jbehave.departmentManager.balanceUserSteps;

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

    @Then("the user checks the balance list item by sku '$sku' has items not visible $examplesTable")
    public void thenTheUserChecksTheBalanceListItemsHasItemsNotVisible(String sku, ExamplesTable examplesTable) {
        balanceSteps.checkItemsAreNotVisible(sku, examplesTable);
        itemsExampleTable = examplesTable;
    }

    @Then("the user checks the balance list item by sku '$sku' has items become visible while hovering")
    public void thenTheUserChecksTheBalanceListItemsHasItemsVisible(String sku) {
        balanceSteps.checkItemsAreVisible(sku, itemsExampleTable);
    }

}
