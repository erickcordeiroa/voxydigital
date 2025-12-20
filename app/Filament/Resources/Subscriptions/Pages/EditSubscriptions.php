<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptions extends EditRecord
{
    protected static string $resource = SubscriptionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->disabled(fn () => $this->record->status === 'cancelled')
                ->tooltip(fn () => $this->record->status === 'cancelled' 
                    ? 'Assinaturas canceladas não podem ser excluídas' 
                    : 'Excluir assinatura'
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Se o status for alterado para 'cancelled', garantir que não seja excluída
        if (isset($data['status']) && $data['status'] === 'cancelled') {
            // Se ends_at não estiver definido, calcular baseado no período pago
            if (empty($data['ends_at']) && !empty($data['next_billing_date'])) {
                $data['ends_at'] = $data['next_billing_date'];
            }
        }

        return $data;
    }
}

