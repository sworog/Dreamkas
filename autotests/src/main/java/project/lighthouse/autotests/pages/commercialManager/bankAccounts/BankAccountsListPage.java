package project.lighthouse.autotests.pages.commercialManager.bankAccounts;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.Textarea;

public class BankAccountsListPage extends CommonPageObject {
    public BankAccountsListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void createNewBankAccountLinkClick() {
        click(By.linkText("Создать расчётный счёт"));
    }

    public void bankAccountListItemByBankAndAccountClick(String bankName, String account) {
        // TODO: как-то находим и кликаем
    }

    public void bankAccountListItemByBankClick(String bankName) {
        click(By.xpath("a[contains(text(), \"" + bankName + "\")]"));
    }
}
