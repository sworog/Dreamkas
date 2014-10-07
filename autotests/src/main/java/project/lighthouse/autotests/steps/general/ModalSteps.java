package project.lighthouse.autotests.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.pageObjects.ModalWindowPageObject;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.pages.catalog.group.modal.ProductCreateModalWindow;
import project.lighthouse.autotests.pages.catalog.group.modal.ProductEditModalWindow;
import project.lighthouse.autotests.pages.pos.ReceiptModalPage;
import project.lighthouse.autotests.pages.pos.ReceiptPositionEditModalWindow;
import project.lighthouse.autotests.pages.pos.RefundModalWindowPage;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceEditModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInEditModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.supplierReturn.SupplierReturnCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.supplierReturn.SupplierReturnEditModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffEditModalWindow;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ModalSteps<T extends ModalWindowPageObject> extends AbstractGeneralSteps<T> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
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
}
