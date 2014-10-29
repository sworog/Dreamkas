package ru.dreamkas.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.common.pageObjects.ModalWindowPageObject;
import ru.dreamkas.elements.bootstrap.SimplePreloader;
import ru.dreamkas.pages.catalog.group.modal.ProductCreateModalWindow;
import ru.dreamkas.pages.catalog.group.modal.ProductEditModalWindow;
import ru.dreamkas.pages.catalog.modal.EditGroupModalPage;
import ru.dreamkas.pages.pos.ReceiptModalPage;
import ru.dreamkas.pages.pos.ReceiptPositionEditModalWindow;
import ru.dreamkas.pages.pos.RefundModalWindowPage;
import ru.dreamkas.pages.stockMovement.modal.invoice.InvoiceCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.invoice.InvoiceEditModalWindow;
import ru.dreamkas.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.stockIn.StockInEditModalWindow;
import ru.dreamkas.pages.stockMovement.modal.supplierReturn.SupplierReturnCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.supplierReturn.SupplierReturnEditModalWindow;
import ru.dreamkas.pages.stockMovement.modal.writeOff.WriteOffCreateModalWindow;
import ru.dreamkas.pages.stockMovement.modal.writeOff.WriteOffEditModalWindow;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ModalSteps<T extends ModalWindowPageObject> extends AbstractGeneralSteps<T> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("редактирования группы", EditGroupModalPage.class);
            put("создания товара", ProductCreateModalWindow.class);
            put("редактирования товара", ProductEditModalWindow.class);
            put("создания приемки", InvoiceCreateModalWindow.class);
            put("редактирования приемки", InvoiceEditModalWindow.class);
            put("создания списания", WriteOffCreateModalWindow.class);
            put("редактирования списания", WriteOffEditModalWindow.class);
            put("создания оприходования", StockInCreateModalWindow.class);
            put("редактирования оприходования", StockInEditModalWindow.class);
            put("создания возврата поставщику", SupplierReturnCreateModalWindow.class);
            put("редактирования возврата поставщику", SupplierReturnEditModalWindow.class);
            put("редактирования товарной позиции", ReceiptPositionEditModalWindow.class);
            put("расчета продажи", ReceiptModalPage.class);
            put("возврата товарной позиции", RefundModalWindowPage.class);
        }};
    }

    @Step
    public void assertTitle(String title) {
        assertThat(getCurrentPageObject().getTitle(), is(title));
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
    public void confirmationClick() {
        getCurrentPageObject().confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void deleteButtonClick() {
        getCurrentPageObject().deleteButtonClick();
    }

    @Step
    public void confirmDeleteButtonClick() {
        getCurrentPageObject().confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void continueButtonClick() {
        getCurrentPageObject().continueButtonClick();
        new SimplePreloader(getDriver()).await();
    }
}
