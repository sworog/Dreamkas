package ru.dreamkas.pages.pos;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.receiptHistory.Receipt;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.handler.field.FieldChecker;
import ru.dreamkas.helper.DateTimeHelper;

public class ReceiptElement extends CommonPageObject {

    public ReceiptElement(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("заголовок чека", new NonType(this, By.className("sale__title")));
        put("способ оплаты", new NonType(this, By.xpath("//*[@name='bankcard' or @name='cash']")));
        put("дата", new NonType(this, By.xpath("//*[@name='date']")) {

            @Override
            public FieldChecker getFieldChecker() {
                return new FieldChecker(this) {

                    @Override
                    public void assertValueEqual(String expectedValue) {
                        String saleDate = ApiStorage.getCustomVariableStorage().getSalesMap().get(expectedValue);
                        String expectedConvertedDate = DateTimeHelper.getExpectedSaleDate(saleDate, "dd MMMM YYYY, HH:mm");
                        super.assertValueEqual(expectedConvertedDate);
                    }
                };
            }
        });
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.className("sale__product")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new Receipt(element);
            }
        });
        put("refundButton", new PrimaryBtnFacade(this, "Возврат"));
    }
}
