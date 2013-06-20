package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.amount.AmountListPage;
import project.lighthouse.autotests.pages.amount.AmountsApi;

public class AmountSteps extends ScenarioSteps {

    AmountListPage amountListPage;
    AmountsApi amountsApi;

    public AmountSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void AmountListPageOpen() {
        amountListPage.open();
    }

    @Step
    public void checkProductWithSkuHasExpectedValue(String skuValue, String name, String expectedValue) {
        amountListPage.checkAmountItemListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        amountListPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void averagePriceCalculation() {
        amountsApi.averagePriceCalculation();
    }
}
