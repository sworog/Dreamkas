package project.lighthouse.autotests.steps.departmentManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.departmentManager.amount.AmountListPage;

public class AmountSteps extends ScenarioSteps {

    AmountListPage amountListPage;

    @Step
    public void AmountListPageOpen() {
        amountListPage.open();
    }

    @Deprecated
    @Step
    public void checkProductWithSkuHasExpectedValue(String skuValue, String name, String expectedValue) {
        amountListPage.checkAmountItemListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        amountListPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }
}
