package project.lighthouse.autotests.pages.pos;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.collection.receiptHistory.Receipt;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.handler.field.FieldChecker;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.storage.Storage;

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
                        String saleDate = Storage.getCustomVariableStorage().getSalesMap().get(expectedValue);
                        String expectedConvertedDate = DateTimeHelper.getExpectedSaleDate(saleDate, "dd MMMM YYYY, HH:mm");
                        super.assertValueEqual(expectedConvertedDate);
                    }
                };
            }
        });
        put("defaultCollection", new AbstractObjectCollection(getDriver(), By.className("sale__product")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new Receipt(element);
            }
        });
        put("refundButton", new PrimaryBtnFacade(this, "Возврат"));
    }
}
