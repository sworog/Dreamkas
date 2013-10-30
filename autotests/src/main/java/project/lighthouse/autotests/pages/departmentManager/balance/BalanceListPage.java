package project.lighthouse.autotests.pages.departmentManager.balance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.notApi.balance.BalanceObjectCollection;

public class BalanceListPage extends CommonPageObject {

    public BalanceListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public BalanceObjectCollection getBalanceObjectCollection() {
        return new BalanceObjectCollection(getDriver(), By.name("amountItem"));
    }

    public void balanceTabClick() {
        click(By.xpath("//*[@rel='balance']"));
    }
}
