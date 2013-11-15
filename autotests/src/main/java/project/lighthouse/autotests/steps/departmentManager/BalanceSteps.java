package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.pages.departmentManager.balance.BalanceListPage;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class BalanceSteps extends ScenarioSteps {

    BalanceListPage balanceListPage;

    public BalanceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        balanceListPage.getBalanceObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void balanceTabClick() {
        balanceListPage.balanceTabClick();
    }

    @Step
    public void balanceTabIsNotVisible() {
        try {
            balanceListPage.balanceTabClick();
            Assert.fail("Products balance tab is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void clickPropertyByLocator(String locator) {
        balanceListPage.getBalanceObjectCollection().clickByLocator(locator);
    }

    @Step
    public void checkItemsAreNotVisible(String locator, ExamplesTable examplesTable) {
        WebElement element = balanceListPage.getBalanceObjectCollection().getAbstractObjectByLocator(locator).getElement();
        Waiter waiter = new Waiter(getDriver(), 0);
        for (Map<String, String> row : examplesTable.getRows()) {
            String item = row.get("items");
            waiter.invisibilityOfElementLocated(element, By.xpath("//*[@class='table__rowHiddenElement table_subCategoryBalance__valueHint' and normalize-space(text())='" + item + "']"));
        }
    }

    @Step
    public void checkItemsAreVisible(String locator, ExamplesTable examplesTable) {
        //TODO need to be refactored (abstractObject webElement need to be updated)
        Waiter waiter = new Waiter(getDriver());
        List<Map<String, String>> notFoundRows = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            String item = row.get("items");
            new Actions(getDriver()).moveToElement(balanceListPage.getBalanceObjectCollection().getAbstractObjectByLocator(locator).getElement()).build().perform();
            List<WebElement> webElementList = waiter.getVisibleWebElements(By.xpath("//*[@name='inventoryItem']//*[@model-attribute='product.sku' and text()='" + locator + "']/../../..//*[@class='table__rowHiddenElement table_subCategoryBalance__valueHint']"));
            for (WebElement element : webElementList) {
                if (element.getText().equals(item)) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                notFoundRows.add(row);
            }
        }
        if (notFoundRows.size() > 0) {
            String errorMessage = String.format("These rows are not found: '%s'.", notFoundRows.toString());
            Assert.fail(errorMessage);
        }
    }
}
