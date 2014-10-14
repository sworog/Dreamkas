package ru.dreamkas.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.stockMovement.StockMovementProductObject;
import ru.dreamkas.elements.bootstrap.SimplePreloader;
import ru.dreamkas.helper.DateTimeHelper;
import ru.dreamkas.pages.stockMovement.modal.PayableStockMovementModalPage;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;
import ru.dreamkas.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.stockIn.StockInEditModalWindow;
import ru.dreamkas.pages.stockMovement.modal.supplierReturn.SupplierReturnCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.supplierReturn.SupplierReturnEditModalWindow;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockMovementSteps<T extends StockMovementModalPage> extends AbstractGeneralSteps<T> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("создания оприходования", StockInCreateModalWindow.class);
            put("редактирования оприходования", StockInEditModalWindow.class);
            put("создания оприходования", StockInCreateModalWindow.class);
            put("редактирования оприходования", StockInEditModalWindow.class);
            put("создания возврата поставщику", SupplierReturnCreateModalWindow.class);
            put("редактирования возврата поставщику", SupplierReturnEditModalWindow.class);
        }};
    }

    public AbstractObjectCollection getProductCollection() {
        AbstractObjectCollection productCollection;
        try {
            productCollection = getCurrentPageObject().getProductCollection();
        } catch (StaleElementReferenceException e) {
            productCollection = getCurrentPageObject().getProductCollection();
        }
        return productCollection;
    }

    protected StockMovementProductObject locateStockMovementObjectByName(String name) {
        return (StockMovementProductObject) getProductCollection().getAbstractObjectByLocator(name);
    }

    @Step
    public void clickAddProductButton() {
        getCurrentPageObject().addProductButtonClick();
        new SimplePreloader(getDriver()).await();
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
        new SimplePreloader(getDriver()).await();
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

    @Step
    public void clickPaidCheckBox() {
        if (getCurrentPageObject() instanceof PayableStockMovementModalPage) {
            ((PayableStockMovementModalPage) getCurrentPageObject()).clickPaidCheckBox();
        } else {
            throw new AssertionError("This modal window does not have paid checkbox");
        }
    }
}
