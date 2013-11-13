package project.lighthouse.autotests.jbehave.departmentManager.balanceUserSteps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.BalanceSteps;

public class WhenBalanceSteps {

    @Steps
    BalanceSteps balanceSteps;

    @When("the user opens product balance tab")
    public void whenTheUserOpensProductBalanceTab() {
        balanceSteps.balanceTabClick();
    }

    @When("the user clicks on the product name link of the inventory table item by '$sku'")
    public void whenTheUserClicksOnTheProductNameLinkOfTheInventoryTableItemBySku(String sku) {
        balanceSteps.clickPropertyByLocator(sku, "productName");
    }
}
