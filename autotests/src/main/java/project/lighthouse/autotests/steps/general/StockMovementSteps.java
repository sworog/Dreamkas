package project.lighthouse.autotests.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementWebObject;
import project.lighthouse.autotests.objects.web.stockMovement.stockIn.StockInProduct;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInEditModalWindow;
import project.lighthouse.autotests.storage.Storage;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockMovementSteps extends AbstractGeneralSteps<StockMovementModalPage> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("создания оприходования", StockInCreateModalWindow.class);
            put("редактирования оприходования", StockInEditModalWindow.class);
            put("создания оприходования", StockInCreateModalWindow.class);
            put("редактирования оприходования", StockInEditModalWindow.class);
        }};
    }

    protected AbstractObjectCollection getProductCollection() {
        AbstractObjectCollection productCollection;
        try {
            productCollection = getCurrentPageObject().getProductCollection();
        } catch (StaleElementReferenceException e) {
            productCollection = getCurrentPageObject().getProductCollection();
        }
        return productCollection;
    }

    protected StockMovementWebObject locateStockMovementObjectByName(String name) {
        return (StockMovementWebObject) getProductCollection().getAbstractObjectByLocator(name);
    }

    @Step
    public void clickAddProductButton() {
        getCurrentPageObject().addProductButtonClick();
    }

    @Step
    public void productCollectionExactCompare(ExamplesTable examplesTable) {
        getProductCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertTotalSum(String totalSum) {
        assertThat(getCurrentPageObject().getTotalSum(), is(totalSum));
    }

    @Step
    public void clickCreateButton() {
        getCurrentPageObject().confirmationOkClick();
    }


    @Step
    public void assertStockInDateIsNowDate() {
        getCurrentPageObject().checkValue("date", DateTimeHelper.getDate("todayDate"));
    }

    @Step
    public void clickStockMovementProductDeleteIcon(String name) {
        locateStockMovementObjectByName(name).clickDeleteIcon();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertProductRowsCount(Integer expectedCount) {
        assertThat(getCurrentPageObject().getProductRowsCount(), is(expectedCount));
    }
}
