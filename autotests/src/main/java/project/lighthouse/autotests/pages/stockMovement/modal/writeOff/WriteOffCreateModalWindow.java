package project.lighthouse.autotests.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.objects.web.writeOffProduct.WriteOffProductCollection;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;

public class WriteOffCreateModalWindow extends StockMovementModalPage {

    public WriteOffCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_writeOffAdd']";
    }

    @Override
    public void createElements() {
        super.createElements();
        put("cause", new Input(this, "//*[@class='writeOffProductForm']//*[@name='cause']"));
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Списать");
    }

    @Override
    public void addProductButtonClick() {
        addProductButtonClick("addWriteOffProduct");
    }

    @Override
    public WriteOffProductCollection getProductCollection() {
        return new WriteOffProductCollection(getDriver());
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_writeOffProducts");
    }

    @Override
    public String getTotalSum() {
        String xpath = String.format("%s//*[@class='writeOff__totalSum']", modalWindowXpath());
        return findVisibleElement(By.xpath(xpath)).getText();
    }
}
