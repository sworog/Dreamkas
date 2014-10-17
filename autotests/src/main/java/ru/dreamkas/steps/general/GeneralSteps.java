package ru.dreamkas.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.common.pageObjects.GeneralPageObject;
import ru.dreamkas.pages.pos.PosLaunchPage;
import ru.dreamkas.pages.pos.PosPage;
import ru.dreamkas.pages.pos.PosSaleHistoryPage;
import ru.dreamkas.pages.pos.ReceiptElement;
import ru.dreamkas.pages.reports.ReportsMainPage;
import ru.dreamkas.pages.reports.grossMarginSales.GrossMarginSalesByGroupsReportPage;
import ru.dreamkas.pages.reports.grossMarginSales.GrossMarginSalesByProductsReportPage;
import ru.dreamkas.pages.reports.stockBalance.StockBalanceReport;
import ru.dreamkas.pages.stockMovement.StockMovementPage;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class GeneralSteps<T extends GeneralPageObject> extends AbstractGeneralSteps<T> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("товародвижение", StockMovementPage.class);
            put("выбора кассы", PosLaunchPage.class);
            put("выбранной кассы", PosPage.class);
            put("истории продаж кассы", PosSaleHistoryPage.class);
            put("чека", ReceiptElement.class);
            put("странице отчетов", ReportsMainPage.class);
            put("странице отчета остатка товаров", StockBalanceReport.class);
            put("странице отчета по продажам и прибыли по товарам внутри группы", GrossMarginSalesByProductsReportPage.class);
            put("отчета по продажам и прибыли по товарам группы", GrossMarginSalesByGroupsReportPage.class);
        }};
    }

    @Step
    public void input(String elementName, String value) {
        getCurrentPageObject().input(elementName, value);
    }

    @Step
    public void input(ExamplesTable fieldInputTable) {
        getCurrentPageObject().input(fieldInputTable);
    }

    @Step
    public void checkValue(String element, String value) {
        getCurrentPageObject().checkValue(element, value);
    }

    @Step
    public void checkValues(ExamplesTable examplesTable) {
        getCurrentPageObject().checkValues(examplesTable);
    }

    @Step
    public void checkItemErrorMessage(String elementName, String errorMessage) {
        getCurrentPageObject().checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void assertTitle(String title) {
        assertThat(getCurrentPageObject().getTitle(), is(title));
    }

    @Step
    public void elementShouldBeVisible(String elementName) {
        getCurrentPageObject().elementShouldBeVisible(elementName);
    }

    @Step
    public void elementShouldBeNotVisible(String elementName) {
        getCurrentPageObject().elementShouldBeNotVisible(elementName);
    }

    @Step
    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        getCurrentPageObject().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        getCurrentPageObject().compareWithExampleTable(examplesTable);
    }

    @Step
    public void clickOnCollectionObjectByLocator(String locator) {
        getCurrentPageObject().clickOnCollectionObjectByLocator(locator);
    }

    @Step
    public void assertCommonItemAttributeValue(String commonItemName, String attribute, String value) {
        assertThat(
                getCurrentPageObject().getCommonItemAttributeValue(commonItemName, attribute),
                is(value));
    }

    @Step
    public void clickOnCommonItemWihName(String commonItemName) {
        getCurrentPageObject().clickOnCommonItemWihName(commonItemName);
    }
}
