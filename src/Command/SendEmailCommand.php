<?php
namespace App\Command;

use App\Entity\Aircraft;
use App\Entity\AircraftOperating;
use App\Entity\EmailSubsription;
use App\Entity\Parts;
use App\Entity\PartsOperating;
use App\Repository\AircraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;


class SendEmailCommand extends Command
{
    public static $defaultName='app:send-email';
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {

        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $subscribers = $this->entityManager->getRepository(EmailSubsription::class)->findAll();
        $email = (new Email())
            ->from('avia-agat@yandex.ru')
            ->to()
            ->subject('Проблемные воздушные суда');
        foreach ($subscribers as $m)
        {
            $email->addTo($m->getEmail());
        }


        $aircrafts = $this->entityManager->getRepository(Aircraft::class)->findAll();
        $dangerAc = array();
        foreach ($aircrafts as $a)
        {

            if ($this->getAircraftStatus($a) == 'danger')
            {
                $status = 'danger';
                array_push($dangerAc,
                    '<a style="text-decoration:none; color: red; display: block;" href="https://www.agat-avia.ru/aircrafts/'.
                    $a->getId().'?ac='.
                    $a->getId().'">'.
                    $a->getBoardNum().' </a><br>');
            }
        }
        if($status == 'danger')
        {
            $email->html('
    <p>Воздушные суда, у которых обнаружились проблемы:</p>'.implode($dangerAc));
            $this->mailer->send($email);
            $output->writeln('Отправилось');
            return 1;
        }
        $output->writeln('Не отправилось');

        return 0;


    }
    public function getAircraftStatus(Aircraft $aircraft)
    {
        $em = $this->entityManager;
        $today = new \DateTime();
        $operating = $em->getRepository(AircraftOperating::class)->getLastAircraftOperating($aircraft);
        $overhaul_diff = $aircraft->getOverhaulExpDate()->diff(new \DateTime())->format("%a");
        $assigned_diff = $aircraft->getAssignedExpDate()->diff(new \DateTime())->format("%a");
        $lg_diff = $aircraft->getLgExpDate()->diff(new \DateTime())->format("%a");
        if($operating->getOverhaulRes() >= $aircraft->getOverhaulRes()*0.8 ||
            $operating->getOverhaulRes() >= $aircraft->getOverhaulRes() ||
            $operating->getTotalRes() >= $aircraft->getAssignedRes()*0.8 ||
            $operating->getTotalRes() >= $aircraft->getAssignedRes() ||
            $overhaul_diff <= 30 || $assigned_diff <= 30 || $lg_diff <= 30 ||
            \DateTime::createFromInterface($aircraft->getOverhaulExpDate()) < $today ||
            \DateTime::createFromInterface($aircraft->getAssignedExpDate()) < $today)
        {
            return "danger";
        }
        $parts = $aircraft->getParts();
        if ($parts != null)
        {
            foreach ($parts as $part)
            {
                $part->setStatus($this->getPartStatus($part));
                if ($part->getStatus()=='danger')
                {
                    return "danger";
                }
            }
            return null;
        }
        return null;


    }
    public function getPartStatus(Parts $part)
    {
        $em = $this->entityManager;
        $today = new \DateTime();
        $operating = $em->getRepository(PartsOperating::class)->getLastPartsOperating($part);
        if($operating->getOverhaulRes() >= $part->getOverhaulRes()*0.8 ||
            $operating->getOverhaulRes() >= $part->getOverhaulRes() ||
            $operating->getTotalRes() >= $part->getAssignedRes()*0.8 ||
            $operating->getTotalRes() >= $part->getAssignedRes() ||
            \DateTime::createFromInterface($part->getAssignedExpDate()) < $today||
            \DateTime::createFromInterface($part->getOverhaulExpDate()) < $today)
        {
            return "danger";
        } return null;
    }

}