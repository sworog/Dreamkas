package ru.dreamkas.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.writeOffProduct.WriteOffProductCollection;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;

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
